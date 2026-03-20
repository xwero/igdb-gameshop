<?php

declare(strict_types=1);

use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTO;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTO;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\ImportTablesDTO;
use Xwero\IgdbGameshop\PIM\Tests\Unit\Data\Assets\TestProductCovers;
use Xwero\IgdbGameshop\PIM\Tests\Unit\Data\Assets\TestProducts;
use Xwero\IgdbGameshop\Shared\Data\TempFiles;
use function Xwero\IgdbGameshop\PIM\Data\Migration\IGDBDataToDatabase;

it('handles an import', function () {
    // setup
    $customDir = 'test_temp_' . uniqid();
    $tempFiles = new TempFiles($customDir);

    $games = new GamesDTOCollection(
        new GamesDTO(0, '[{"id": 1, "name": "Game 1"}]'),
        new GamesDTO(1, '[{"id": 2, "name": "Game 2"}]'),
    );

    $tempFiles->multiStoreGames($games);

    $covers1 = [
        ['gameId' => 1, 'url' => 'http://example.com/cover1.jpg', 'width' => 1920, 'height' => 1080],
    ];

    $covers2 = [
        ['gameId' => 2, 'url' => 'http://example.com/cover2.jpg', 'width' => 1920, 'height' => 1080],
    ];

    $response1 = new GameCoversResponseDTO(0, $covers1);
    $response2 = new GameCoversResponseDTO(1, $covers2);
    $collection = new GameCoversResponseDTOCollection($response1, $response2);

    $tempFiles->multiStoreCovers($collection);

    $gamesAndCoversCollection = $tempFiles->getGamesAndCovers();

    $_ENV['DATABASE_DSN'] = 'sqlite::memory:';
    $importTables = new ImportTablesDTO(new TestProducts(), new TestProductCovers());

    // Test
    $succes = IGDBDataToDatabase($gamesAndCoversCollection, $importTables);

    expect($succes)->toBe(true);

    // Cleanup
    unlink($customDir . '/games_0.json');
    unlink($customDir . '/covers_0.csv');
    unlink($customDir . '/games_1.json');
    unlink($customDir . '/covers_1.csv');
    rmdir($customDir);
});