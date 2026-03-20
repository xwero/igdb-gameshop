<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\Shared\Tests\Unit;

use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTO;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTOCollection;
use Xwero\IgdbGameshop\Shared\Data\TempFiles;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTO;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTOCollection;

beforeEach(function () {
    $this->customDir = 'test_temp_' . uniqid();
    $this->tempFiles = new TempFiles($this->customDir);
});

afterEach(function () {
    rmdir($this->customDir);
});

it('constructs with default temp directory', function() {
    expect($this->tempFiles)->toBeInstanceOf(TempFiles::class);
});

it('creates temp directory if it does not exist', function() {
    expect(is_dir($this->customDir))->toBeTrue();
});

it('stores multiple games successfully', function() {
    $games = new GamesDTOCollection(
        new GamesDTO(0, '{"name": "Game 1 content"}'),
        new GamesDTO(1, '{"name": "Game 2 content"}')
    );
    
    $result = $this->tempFiles->multiStoreGames($games);
    
    expect($result)->toBeTrue();
    
    // Verify files were created - note: the actual file names use $game->offset, not $game->content
    expect(file_exists($this->customDir . '/games_0.json'))->toBeTrue();
    expect(file_exists($this->customDir . '/games_1.json'))->toBeTrue();
    
    // Cleanup
    unlink($this->customDir . '/games_0.json');
    unlink($this->customDir . '/games_1.json');
});

it('handles empty games collection', function() {
    $games = new GamesDTOCollection();
    $result = $this->tempFiles->multiStoreGames($games);
    
    expect($result)->toBeTrue();
});

it('returns empty array when no games files exist', function() {
    $result = $this->tempFiles->getGames();

    expect($result->isEmpty())->toBeTrue();
});

it('returns array with items when there are games files', function() {
    $games = new GamesDTOCollection(
        new GamesDTO(0, '{"name": "Game 1 content"}'),
        new GamesDTO(1, '{"name": "Game 2 content"}')
    );

    $this->tempFiles->multiStoreGames($games);

    $result = $this->tempFiles->getGames();

    expect(count($result->toArray()))->toBe(2);

    // Cleanup
    unlink($this->customDir . '/games_0.json');
    unlink($this->customDir . '/games_1.json');
});

it('stores multiple covers successfully', function() {
    $covers1 = [
        ['gameId' => 1, 'url' => 'http://example.com/cover1.jpg', 'width' => 100, 'height' => 200]
    ];
    $covers2 = [
        ['gameId' => 2, 'url' => 'http://example.com/cover2.jpg', 'width' => 200, 'height' => 400]
    ];

    $response1 = new GameCoversResponseDTO(0, $covers1);
    $response2 = new GameCoversResponseDTO(1, $covers2);
    $collection = new GameCoversResponseDTOCollection($response1, $response2);

    $result = $this->tempFiles->multiStoreCovers($collection);

    expect($result)->toBeTrue();

    // Verify files were created
    expect(file_exists($this->customDir . '/covers_0.csv'))->toBeTrue();
    expect(file_exists($this->customDir . '/covers_1.csv'))->toBeTrue();

    // Verify content
    $content0 = file_get_contents($this->customDir . '/covers_0.csv');
    $content1 = file_get_contents($this->customDir . '/covers_1.csv');

    expect($content0)->toContain('1,http://example.com/cover1.jpg,100,200');
    expect($content1)->toContain('2,http://example.com/cover2.jpg,200,400');

    // Cleanup
    unlink($this->customDir . '/covers_0.csv');
    unlink($this->customDir . '/covers_1.csv');
});

it('handles empty covers collection', function() {
    $collection = new GameCoversResponseDTOCollection();
    $result = $this->tempFiles->multiStoreCovers($collection);

    expect($result)->toBeTrue();
});

it('handles covers with multiple entries', function() {
    $covers = [
        ['gameId' => 1, 'url' => 'http://example.com/cover1.jpg', 'width' => 100, 'height' => 200],
        ['gameId' => 2, 'url' => 'http://example.com/cover2.jpg', 'width' => 200, 'height' => 400],
        ['gameId' => 3, 'url' => 'http://example.com/cover3.jpg', 'width' => 300, 'height' => 600]
    ];

    $response = new GameCoversResponseDTO(0, $covers);
    $collection = new GameCoversResponseDTOCollection($response);

    $result = $this->tempFiles->multiStoreCovers($collection);

    expect($result)->toBeTrue();

    // Verify content
    $content = file_get_contents($this->customDir . '/covers_0.csv');

    expect($content)->toContain('1,http://example.com/cover1.jpg,100,200');
    expect($content)->toContain('2,http://example.com/cover2.jpg,200,400');
    expect($content)->toContain('3,http://example.com/cover3.jpg,300,600');

    // Cleanup
    unlink($this->customDir . '/covers_0.csv');
});

it('returns empty collection when only games files exist', function() {
    $games = new GamesDTOCollection(
        new GamesDTO(0, '{"id": 1, "name": "Game 1 content"}'),
    );

    $this->tempFiles->multiStoreGames($games);

    $result = $this->tempFiles->getGamesAndCovers();

    expect($result->isEmpty())->toBeTrue();

    // Cleanup
    unlink($this->customDir . '/games_0.json');
});

it('returns empty collection when only covers files exist', function() {
    $covers1 = [
        ['gameId' => 1, 'url' => 'http://example.com/cover1.jpg', 'width' => 100, 'height' => 200]
    ];

    $response1 = new GameCoversResponseDTO(0, $covers1);
    $collection = new GameCoversResponseDTOCollection($response1);

    $this->tempFiles->multiStoreCovers($collection);

    $result = $this->tempFiles->getGamesAndCovers();

    expect($result->isEmpty())->toBeTrue();

    // Cleanup
    unlink($this->customDir . '/covers_0.csv');
});

it('returns collection with matched games and covers', function() {
    $games = new GamesDTOCollection(
        new GamesDTO(0, '{"id": 1, "name": "Game 1"}'),
        new GamesDTO(1, '{"id": 2, "name": "Game 2"}'),
    );

    $this->tempFiles->multiStoreGames($games);

    $covers1 = [
        ['gameId' => 1, 'url' => 'http://example.com/cover1.jpg', 'width' => 1920, 'height' => 1080],
    ];

    $covers2 = [
        ['gameId' => 2, 'url' => 'http://example.com/cover2.jpg', 'width' => 1920, 'height' => 1080],
    ];

    $response1 = new GameCoversResponseDTO(0, $covers1);
    $response2 = new GameCoversResponseDTO(1, $covers2);
    $collection = new GameCoversResponseDTOCollection($response1, $response2);

    $this->tempFiles->multiStoreCovers($collection);

    $result = $this->tempFiles->getGamesAndCovers();

    expect($result->isEmpty())->toBeFalse();

    $rcollection = $result->toArray();

    expect(count($rcollection))->toBe(2);

    $dto1 = $rcollection[0];
    $dto2 = $rcollection[1];

    expect($dto1->games['name'])->toBe('Game 1');
    expect($dto1->covers[0]['url'])->toBe('http://example.com/cover1.jpg');
    expect($dto2->games['name'])->toBe('Game 2');

    // Cleanup
    unlink($this->customDir . '/games_0.json');
    unlink($this->customDir . '/covers_0.csv');
    unlink($this->customDir . '/games_1.json');
    unlink($this->customDir . '/covers_1.csv');
});

it('ignores unmatched files', function() {
    $games = new GamesDTOCollection(
        new GamesDTO(0, '{"id": 1, "name": "Game 1"}'),
        new GamesDTO(1, '{"id": 2, "name": "Game 2"}'),
    );

    $this->tempFiles->multiStoreGames($games);

    $covers1 = [
        ['gameId' => 1, 'url' => 'http://example.com/cover1.jpg', 'width' => 1920, 'height' => 1080],
    ];

    $covers2 = [
        ['gameId' => 2, 'url' => 'http://example.com/cover2.jpg', 'width' => 1920, 'height' => 1080],
    ];

    $response1 = new GameCoversResponseDTO(0, $covers1);
    $response2 = new GameCoversResponseDTO(2, $covers2);
    $collection = new GameCoversResponseDTOCollection($response1, $response2);

    $this->tempFiles->multiStoreCovers($collection);

    $result = $this->tempFiles->getGamesAndCovers();

    expect($result->isEmpty())->toBeFalse();
    expect(count($result->toArray()))->toBe(1);

    // Cleanup
    unlink($this->customDir . '/games_0.json');
    unlink($this->customDir . '/covers_0.csv');
    unlink($this->customDir . '/games_1.json');
    unlink($this->customDir . '/covers_2.csv');
});