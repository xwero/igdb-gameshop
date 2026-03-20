<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Xwero\IgdbGameshop\Shared\Data\IGDBOAuth;
use Xwero\IgdbGameshop\PIM\Data\IGDBGames;
use Xwero\IgdbGameshop\Shared\Data\TempFiles;

class IGDBGamesCommand2 extends Command
{
    protected function configure(): void
    {
        $this->setName('igdb:import-games2')
            ->setDescription('Imports games from IGDB API')
            ->addArgument('twitchId', InputArgument::REQUIRED, 'Twitch ID')
            ->addArgument('oauthSecret', InputArgument::REQUIRED, 'Oauth secret')
            ->addOption('tempDir', null, InputOption::VALUE_REQUIRED, 'Temp directory');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $twitchId = $input->getArgument('twitchId');
        $oauthSecret = $input->getArgument('oauthSecret');

        $oauth = new IGDBOAuth($twitchId, $oauthSecret);
        $accessToken = $oauth->getAccessToken();

        if ($accessToken === null) {
            $output->writeln('Failed to get valid access token.');
            return Command::FAILURE;
        }

        $gamesEndpoint = new IGDBGames($accessToken);
        $totalGames = 355799;
        $limit = 400;
        $maxParallelRequests = 3;
        $attempts = 0;
        $maxAttempts = 3;
        $tempDir = $input->getOption('tempDir');
        $tempFiles = is_string($tempDir) ? new TempFiles($tempDir) : new TempFiles();
        
        for ($offset = 0; $offset < $totalGames; $offset += $limit * $maxParallelRequests) {
            $success = false;
            
            while (!$success && $attempts < $maxAttempts) {
                $data = $gamesEndpoint->fetchMultipleGames2($twitchId, $limit, $offset, $maxParallelRequests);
                
                if ($data->isEmpty()) {
                    $attempts++;
                    if ($attempts >= $maxAttempts) {
                        $output->writeln('Maximum retries reached. Check credentials or the API is down.');
                        return Command::FAILURE;
                    }
                    
                    // Get new access token and retry
                    $accessToken = $oauth->getAccessToken();
                    if ($accessToken === null) {
                        $output->writeln('Failed to get a new access token for retry.');
                        return Command::FAILURE;
                    }
                    $gamesEndpoint = new IGDBGames($accessToken);
                } else {
                    $success = true;
                    $attempts = 0; // Reset attempts counter

                    $tempFiles->multiStoreGames($data);
                }
            }
        }
        
        $output->writeln('Game import completed successfully.');
        return Command::SUCCESS;
    }
}