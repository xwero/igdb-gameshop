<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Data\Migration;


use Exception;
use Xwero\IgdbGameshop\PIM\DTO\DatabaseCoverDTO;
use Xwero\IgdbGameshop\PIM\DTO\DatabaseCoverDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\ImportTablesDTO;

function IGDBDataToDatabase(GamesAndCoversFileDTOCollection $gamesAndCoversCollection, ImportTablesDTO $tables): bool
{
    try {
        foreach ($gamesAndCoversCollection->toArray() as $gamesAndCovers) {
            // insert handles duplicate game ids
            $insertedGames = $tables->products->multiInsert($gamesAndCovers->games);
            $igdbToDatabaseIdMap = [];

            foreach ($insertedGames as $insert) {
                $igdbToDatabaseIdMap[$insert['igdb_id']] = $insert['id'];
            }

            $coversToInsert = [];

            foreach ($gamesAndCovers->covers as $cover) {
                $gameId = $cover['gameId'];
                if (isset($igdbToDatabaseIdMap[$gameId])) {
                    $coversToInsert[] = new DatabaseCoverDTO($igdbToDatabaseIdMap[$gameId], $cover['url'], (int) $cover['width'], (int) $cover['height']);
                }
            }

            $coverResults = $tables->productCovers->multiInsert(new DatabaseCoverDTOCollection(...$coversToInsert));

            return $coverResults == count($coversToInsert);
            // When the database can't handle the amount of inserts throttling can be used.
            // A crude solution is sleep(1)
        }
    }catch (\Throwable $exception){
        // log the error
        var_dump($exception);
        return false;
    }

    return false;
}