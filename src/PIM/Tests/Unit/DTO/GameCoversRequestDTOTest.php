<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTO;

it('constructs with valid offset and games array', function() {
    $dto = new GameCoversRequestDTO(0, [1, 2, 3]);
    
    expect($dto)->toBeInstanceOf(GameCoversRequestDTO::class);
    expect($dto->offset)->toBe(0);
    expect($dto->games)->toBe([1, 2, 3]);
});

it('throws an exception when the games array is empty', function() {
    $dto = new GameCoversRequestDTO(0, []);
})->throws(\InvalidArgumentException::class);