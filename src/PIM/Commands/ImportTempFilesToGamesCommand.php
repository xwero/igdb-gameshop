<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Xwero\IgdbGameshop\Shared\Data\TempFiles;

class ImportTempFilesToGamesCommand extends Command
{
    protected static $defaultName = 'igdb:import-temp-to-games';
    protected static $defaultDescription = 'Import data from temporary files to games and games_cover tables using generator';
    
    protected function configure(): void
    {
        $this->setName(self::$defaultName)
             ->setDescription(self::$defaultDescription)
            ->addOption('tempDir', null, InputOption::VALUE_REQUIRED, 'Temp directory', 'temp');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $tempDir = $input->getOption('tempDir');
            $tempFiles = new TempFiles($tempDir);
            
            // Create database connection with proper settings for concurrent access
            $databasePath = 'var/data.db';
            $pdo = new \PDO("sqlite:" . $databasePath);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_TIMEOUT, 30); // 30 second timeout for locked database
            
            // Enable WAL mode for better concurrency
            $pdo->exec("PRAGMA journal_mode=WAL;");
            $pdo->exec("PRAGMA synchronous=NORMAL;");
            $pdo->exec("PRAGMA busy_timeout=5000;"); // 5 second busy timeout
            
            // Create tables if they don't exist
            $this->createTables($pdo);
            
            // Use generator to read files
            $filesProcessed = 0;
            $gamesInserted = 0;
            $coversInserted = 0;
            
            foreach ($this->readTempFilesGenerator($tempDir) as $fileData) {
                $filesProcessed++;
                
                // Begin transaction
                $pdo->beginTransaction();
                
                try {
                    // Insert games and covers together
                    $result = $this->insertGamesWithCovers($pdo, $fileData);
                    $gamesInserted += $result['games'];
                    $coversInserted += $result['covers'];
                    
                    // Commit transaction
                    $pdo->commit();
                    
                    $output->writeln(sprintf('Processed file: %d games, %d covers', $result['games'], $result['covers']));
                } catch (\Exception $e) {
                    // Rollback transaction on error
                    $pdo->rollBack();
                    $output->writeln(sprintf('<error>Error processing file: %s</error>', $e->getMessage()));
                    continue;
                }
            }
            
            $output->writeln(sprintf('<info>Import completed: %d files, %d games, %d covers inserted</info>', 
                $filesProcessed, $gamesInserted, $coversInserted));
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Error: %s</error>', $e->getMessage()));
            return Command::FAILURE;
        }
    }
    
    private function createTables(\PDO $pdo): void
    {
        // Create games table
        $pdo->exec('
            CREATE TABLE IF NOT EXISTS games (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                igdb_id INTEGER UNIQUE,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT (datetime("now")),
                updated_at TIMESTAMP DEFAULT (datetime("now"))
            )
        ');
        
        // Create games_cover table
        $pdo->exec('
            CREATE TABLE IF NOT EXISTS games_cover (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                game_id INTEGER NOT NULL,
                url VARCHAR(512) NOT NULL,
                width INTEGER,
                height INTEGER,
                created_at TIMESTAMP DEFAULT (datetime("now")),
                updated_at TIMESTAMP DEFAULT (datetime("now")),
                FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
            )
        ');
        
        // Create index
        $pdo->exec('CREATE INDEX IF NOT EXISTS idx_games_cover_game_id ON games_cover(game_id)');
    }
    
    private function readTempFilesGenerator(string $tempDir): \Generator
    {
        $gamesFiles = glob($tempDir . '/games_*.json') ?: [];
        
        foreach ($gamesFiles as $gameFile) {
            $gameContent = file_get_contents($gameFile);
            
            if ($gameContent !== false) {
                $gamesData = json_decode($gameContent, true);
                
                if (is_array($gamesData) && count($gamesData) > 0) {
                    yield $gamesData;
                }
            }
        }
    }
    
    private function insertGamesWithCovers(\PDO $pdo, array $gamesData): array
    {
        if (empty($gamesData)) {
            return ['games' => 0, 'covers' => 0];
        }
        
        $gamesInserted = 0;
        $coversInserted = 0;
        
        // Prepare statements
        $gameStmt = $pdo->prepare('
            INSERT OR IGNORE INTO games (igdb_id, name, created_at, updated_at) 
            VALUES (:igdb_id, :name, datetime("now"), datetime("now"))
        ');
        
        $coverStmt = $pdo->prepare('
            INSERT INTO games_cover (game_id, url, width, height, created_at, updated_at) 
            VALUES (:game_id, :url, :width, :height, datetime("now"), datetime("now"))
        ');
        
        foreach ($gamesData as $game) {
            try {
                // Insert game
                $gameStmt->execute([
                    ':igdb_id' => $game['id'],
                    ':name' => $game['name']
                ]);
                
                // Get the inserted game ID
                $gameIdStmt = $pdo->prepare('SELECT id FROM games WHERE igdb_id = :igdb_id');
                $gameIdStmt->execute([':igdb_id' => $game['id']]);
                $gameResult = $gameIdStmt->fetch(\PDO::FETCH_ASSOC);
                
                if ($gameResult && isset($gameResult['id'])) {
                    $gamesInserted++;
                    
                    // Insert cover if available
                    if (isset($game['cover']) && is_array($game['cover'])) {
                        $cover = $game['cover'];
                        if (isset($cover['url']) && isset($cover['width']) && isset($cover['height'])) {
                            $coverStmt->execute([
                                ':game_id' => $gameResult['id'],
                                ':url' => $cover['url'],
                                ':width' => $cover['width'],
                                ':height' => $cover['height']
                            ]);
                            $coversInserted++;
                        }
                    }
                }
            } catch (\Exception $e) {
                // Skip duplicate or invalid entries
                continue;
            }
        }
        
        return ['games' => $gamesInserted, 'covers' => $coversInserted];
    }
}