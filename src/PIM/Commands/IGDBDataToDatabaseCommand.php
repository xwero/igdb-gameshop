<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Xwero\IgdbGameshop\PIM\Data\ProductCovers;
use Xwero\IgdbGameshop\PIM\Data\Products;
use Xwero\IgdbGameshop\PIM\DTO\ImportTablesDTO;
use Xwero\IgdbGameshop\Shared\Data\TempFiles;
use function Xwero\IgdbGameshop\PIM\Data\Migration\IGDBDataToDatabase;

class IGDBDataToDatabaseCommand extends Command
{
    protected static $defaultName = 'igdb:data-to-database';
    protected static $defaultDescription = 'Import IGDB games and covers data from temporary files to database';
    
    protected function configure(): void
    {
        $this->setName(self::$defaultName)
             ->setDescription(self::$defaultDescription)
            ->addOption('tempDir', null, InputOption::VALUE_REQUIRED, 'Temp directory');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $tempDir = $input->getOption('tempDir');
            $tempFiles = is_string($tempDir) ? new TempFiles($tempDir) : new TempFiles();
            $gamesAndCoversCollection = $tempFiles->getGamesAndCovers();
            
            if ($gamesAndCoversCollection->isEmpty()) {
                $output->writeln('<error>No games and covers data found in temporary directory</error>');
                return Command::FAILURE;
            }
            
            $output->writeln('<info>Starting database import...</info>');

            $importTables = new ImportTablesDTO(new Products(), new ProductCovers());
            $succes = IGDBDataToDatabase($gamesAndCoversCollection, $importTables);

            if($succes) {
                $output->writeln('<info>Database import completed successfully</info>');
                return Command::SUCCESS;
            }else {
                $output->writeln('<error>Database import failed</error>');
                return Command::FAILURE;
            }
            
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Error: %s</error>', $e->getMessage()));
            return Command::FAILURE;
        }
    }
}