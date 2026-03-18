<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\Shared\Data;

use Xwero\IgdbGameshop\PIM\DTO\GamesDTOCollection;

class TempFiles
{
    public function __construct(private readonly string $tempDirectory = 'temp')
    {
        if (!is_dir($tempDirectory)) {
            mkdir($tempDirectory, 0777, true);
        }
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