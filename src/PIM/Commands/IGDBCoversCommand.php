<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Xwero\IgdbGameshop\Shared\Data\IGDBOAuth;
use Xwero\IgdbGameshop\PIM\Data\IGDBCovers;
use Xwero\IgdbGameshop\Shared\Data\TempFiles;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTO;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTOCollection;

class IGDBCoversCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('igdb:import-covers')
            ->setDescription('Imports game covers from IGDB API')
            ->addArgument('twitchId', InputArgument::REQUIRED, 'Twitch ID')
            ->addArgument('oauthSecret', InputArgument::REQUIRED, 'Oauth secret')
            ->addOption('tempDir', null, InputOption::VALUE_REQUIRED, 'Temp directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tempDir = $input->getOption('tempDir');
        $tempFiles = is_string($tempDir) ? new TempFiles($tempDir) : new TempFiles();
        $gameFiles = $tempFiles->getGames();

        if ($gameFiles->isEmpty()) {
            $output->writeln('No games found. Run igdb:import-games first.');
            return Command::FAILURE;
        }

        $twitchId = $input->getArgument('twitchId');
        $oauthSecret = $input->getArgument('oauthSecret');

        $oauth = new IGDBOAuth($twitchId, $oauthSecret);
        $accessToken = $oauth->getAccessToken();

        if ($accessToken === null) {
            $output->writeln('Failed to get valid access token.');
            return Command::FAILURE;
        }

        $coversEndpoint = new IGDBCovers($accessToken);
        $attempts = 0;
        $maxAttempts = 3;
        $chunkSize = 3;
        $gameFileChunks = array_chunk($gameFiles->toArray(), $chunkSize, true);

        foreach ($gameFileChunks as $chunk) {
            $success = false;
            
            while (!$success && $attempts < $maxAttempts) {
                $requests = [];
                
                foreach ($chunk as $gameFile) {
                    $gameIds = [];
                    foreach ($gameFile->content as $game) {
                        if (isset($game['cover']) && strlen((string)$game['cover']) > 0) {
                            $gameIds[] = $game['id'];
                        }
                    }

                    if (count($gameIds) > 0) {
                        $requests[] = new GameCoversRequestDTO($gameFile->offset, $gameIds);
                    }
                }
                
                if (count($requests) == 0) {
                    $success = true;
                    continue;
                }

                $requestCollection = new GameCoversRequestDTOCollection(...$requests);
                $coversResponse = $coversEndpoint->fetchMultipleCovers($twitchId, $requestCollection);
                
                if ($coversResponse->isEmpty()) {
                    $attempts++;
                    if ($attempts >= $maxAttempts) {
                        $output->writeln('Max retries reached.');
                        return Command::FAILURE;
                    }
                    
                    // Get new access token and retry
                    $accessToken = $oauth->getAccessToken();
                    if ($accessToken === null) {
                        $output->writeln('Failed to get new access token for retry.');
                        return Command::FAILURE;
                    }
                    $coversEndpoint = new IGDBCovers($accessToken);
                } else {
                    $success = true;
                    $attempts = 0; // Reset attempts counter
                    
                    $tempFiles->multiStoreCovers($coversResponse);
                }
            }
        }

        $output->writeln('Game covers import completed successfully.');
        return Command::SUCCESS;
    }
}