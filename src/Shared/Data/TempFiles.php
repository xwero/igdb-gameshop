<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\Shared\Data;

use Xwero\IgdbGameshop\PIM\DTO\GamesDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\GamesFileDTO;
use Xwero\IgdbGameshop\PIM\DTO\GamesFileDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTO;
use Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTOCollection;

class TempFiles
{
    public function __construct(private readonly string $tempDirectory = 'temp')
    {
        if (!is_dir($tempDirectory)) {
            mkdir($tempDirectory, 0777, true);
        }
    }

    public function getGames(): GamesFileDTOCollection
    {
        $gamesFiles = glob($this->tempDirectory . '/games_*.json') ?: [];
        $games = [];

        foreach ($gamesFiles as $file) {
            $content = file_get_contents($file);
            if ($content !== false) {
                $data = json_decode($content, true);
                if (is_array($data) && count($data) > 0) {
                    $filename = basename($file);
                    $offset = (int)str_replace(['games_', '.json'], '', $filename);
                    $games[] = new GamesFileDTO($offset, $data);
                }
            }
        }

        return new GamesFileDTOCollection(...$games);
    }

    public function multiStoreGames(GamesDTOCollection $games): bool
    {
        $files = [];

        foreach ($games->toArray() as $game) {
            $files[] = [
                'name' =>  'games_' . $game->offset . '.json',
                'content' =>  $game->json,
            ];
        }

        return count($this->storeMulti($files)) == 0;
    }

    public function multiStoreCovers(GameCoversResponseDTOCollection $covers): bool
    {
        $files = [];

        foreach ($covers->toArray() as $coverResponse) {
            $csvContent = '';
            foreach ($coverResponse->covers as $cover) {
                $csvContent .= implode(',', [$cover['gameId'], $cover['url'], $cover['width'], $cover['height']]) . "\n";
            }

            $files[] = [
                'name' => 'covers_' . $coverResponse->offset . '.csv',
                'content' => $csvContent,
            ];
        }

        return count($this->storeMulti($files)) == 0;
    }

    public function getGamesAndCovers(): GamesAndCoversFileDTOCollection
    {
        $gamesFiles = glob($this->tempDirectory . '/games_*.json') ?: [];
        $gamesAndCovers = [];

        foreach ($gamesFiles as $gameFile) {
            $filename = basename($gameFile);
            $offset = (int)str_replace(['games_', '.json'], '', $filename);
            
            // Find matching covers file
            $coverFile = $this->tempDirectory . '/covers_' . $offset . '.csv';
            
            if (file_exists($coverFile)) {
                $gameContent = file_get_contents($gameFile);
                $coverContent = file_get_contents($coverFile);
                
                if ($gameContent !== false && $coverContent !== false) {
                    $gamesData = json_decode($gameContent, true);
                    $coversData = [];
                    
                    // Parse CSV covers
                    $coverLines = explode("\n", trim($coverContent));
                    foreach ($coverLines as $line) {
                        if (!empty($line)) {
                            $coverParts = str_getcsv($line, escape: '\\');
                            if (count($coverParts) === 4) {
                                $coversData[] = [
                                    'gameId' => $coverParts[0],
                                    'url' => $coverParts[1],
                                    'width' => $coverParts[2],
                                    'height' => $coverParts[3]
                                ];
                            }
                        }
                    }
                    // There could be an edge case where there are no covers for a game batch.
                    // From IGDB API tests it seems to be unlikely none of the games in a batch have a cover.
                    if (is_array($gamesData) && count($gamesData) > 0 && count($coversData) > 0) {
                        $gamesAndCovers[] = new GamesAndCoversFileDTO($gamesData, $coversData);
                    }
                }
            }
        }

        return new GamesAndCoversFileDTOCollection(...$gamesAndCovers);
    }

    private function storeMulti(array $files): array
    {
        $errors = [];

        foreach ($files as $file) {
            $path = $this->tempDirectory . '/' . $file['name'];
            $status = file_put_contents($path, $file['content'], LOCK_EX);

            if ($status === false) {
              $errors[] = $path;
            }
        }

        return $errors;
    }
}