<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTO;

it('constructs with valid offset and covers array', function() {
    $covers = [
        ['gameId' => 1, 'url' => 'http://example.com/cover1.jpg', 'width' => 100, 'height' => 200]
    ];
    $dto = new GameCoversResponseDTO(0, $covers);
    
    expect($dto)->toBeInstanceOf(GameCoversResponseDTO::class);
    expect($dto->offset)->toBe(0);
    expect($dto->covers)->toBe($covers);
});

it('requires array of covers', function() {
    $covers = [
        ['gameId' => 1, 'url' => 'http://example.com/cover1.jpg', 'width' => 100, 'height' => 200],
        ['gameId' => 2, 'url' => 'http://example.com/cover2.jpg', 'width' => 200, 'height' => 400]
    ];
    $dto = new GameCoversResponseDTO(0, $covers);
    expect($dto->covers)->toBe($covers);
});

it('throws exception when covers array is empty', function() {
    $dto = new GameCoversResponseDTO(0, []);
})->throws(\InvalidArgumentException::class);