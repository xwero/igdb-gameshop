<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\Shared\Tests\Unit;

use Xwero\IgdbGameshop\Shared\Data\TempFiles;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTO;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTOCollection;

it('constructs with default temp directory', function() {
    $tempFiles = new TempFiles();
    expect($tempFiles)->toBeInstanceOf(TempFiles::class);
});

it('constructs with custom temp directory', function() {
    $tempFiles = new TempFiles('custom_temp');
    expect($tempFiles)->toBeInstanceOf(TempFiles::class);
});

it('creates temp directory if it does not exist', function() {
    $customDir = 'test_temp_' . uniqid();
    $tempFiles = new TempFiles($customDir);
    
    expect(is_dir($customDir))->toBeTrue();
    
    // Cleanup
    rmdir($customDir);
});

it('stores multiple games successfully', function() {
    $tempDir = 'test_temp_' . uniqid();
    $tempFiles = new TempFiles($tempDir);
    
    $games = new GamesDTOCollection(
        new GamesDTO(0, '{"name": "Game 1 content"}'),
        new GamesDTO(1, '{"name": "Game 2 content"}')
    );
    
    $result = $tempFiles->multiStoreGames($games);
    
    expect($result)->toBeTrue();
    
    // Verify files were created - note: the actual file names use $game->offset, not $game->content
    expect(file_exists($tempDir . '/games_0.json'))->toBeTrue();
    expect(file_exists($tempDir . '/games_1.json'))->toBeTrue();
    
    // Cleanup
    if (file_exists($tempDir . '/games_0.json')) unlink($tempDir . '/games_0.json');
    if (file_exists($tempDir . '/games_1.json')) unlink($tempDir . '/games_1.json');
    if (is_dir($tempDir)) rmdir($tempDir);
});

it('handles empty games collection', function() {
    $tempDir = 'test_temp_' . uniqid();
    $tempFiles = new TempFiles($tempDir);
    
    $games = new GamesDTOCollection();
    $result = $tempFiles->multiStoreGames($games);
    
    expect($result)->toBeTrue();
    
    // Cleanup
    rmdir($tempDir);
});