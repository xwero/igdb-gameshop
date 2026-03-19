<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\Data\IGDBCovers;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTO;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTO;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTOCollection;

it('constructs with access token', function() {
    $covers = new IGDBCovers('test_access_token');
    expect($covers)->toBeInstanceOf(IGDBCovers::class);
});

it('requires non-empty access token', function() {
    $covers = new IGDBCovers('');
})->throws(\InvalidArgumentException::class);

it('returns empty collection when no valid responses', function() {
    $covers = new IGDBCovers('invalid_token');
    
    $request = new GameCoversRequestDTO(0, [1, 2, 3]);
    $requestCollection = new GameCoversRequestDTOCollection($request);
    
    $result = $covers->fetchMultipleCovers('invalid_twitch_id', $requestCollection);

    expect($result->isEmpty())->toBeTrue();
});