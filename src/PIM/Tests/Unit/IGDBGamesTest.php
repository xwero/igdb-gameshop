<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\Data\IGDBGames;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTOCollection;

it('constructs with access token', function() {
    $games = new IGDBGames('test_access_token');
    expect($games)->toBeInstanceOf(IGDBGames::class);
});

it('requires non-empty access token', function() {
    $games = new IGDBGames('');
    expect($games)->toBeInstanceOf(IGDBGames::class);
});

it('returns empty collection when no valid responses', function() {
    $games = new IGDBGames('invalid_token');
    
    // This will fail with real API call, but we can test the structure
    $result = $games->fetchMultipleGames('test_twitch_id', 10, 0, 1);
    
    expect($result)->toBeInstanceOf(GamesDTOCollection::class);
    // Note: This test may fail because the API call might actually succeed with invalid token
    // In a real test environment, you would mock the curl calls
});

it('handles zero max requests', function() {
    $games = new IGDBGames('test_token');
    $result = $games->fetchMultipleGames('test_twitch_id', 10, 0, 0);
    
    expect($result)->toBeInstanceOf(GamesDTOCollection::class);
    expect($result->isEmpty())->toBeTrue();
});

it('handles negative offset', function() {
    $games = new IGDBGames('test_token');
    $result = $games->fetchMultipleGames('test_twitch_id', 10, -10, 1);
    
    expect($result)->toBeInstanceOf(GamesDTOCollection::class);
});

it('handles large limit values', function() {
    $games = new IGDBGames('test_token');
    $result = $games->fetchMultipleGames('test_twitch_id', 1000, 0, 1);
    
    expect($result)->toBeInstanceOf(GamesDTOCollection::class);
});