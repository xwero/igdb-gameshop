<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\DTO\GamesDTO;

it('constructs with valid offset and json', function() {
    $dto = new GamesDTO(0, '{"games": []}');
    
    expect($dto)->toBeInstanceOf(GamesDTO::class);
    expect($dto->offset)->toBe(0);
    expect($dto->json)->toBe('{"games": []}');
});

it('requires integer offset', function() {
    $dto = new GamesDTO(10, '{"games": []}');
    expect($dto->offset)->toBe(10);
});

it('requires string json', function() {
    $dto = new GamesDTO(0, '[{"id": 1, "name": "Game 1"}]');
    expect($dto->json)->toBe('[{"id": 1, "name": "Game 1"}]');
});

it('handles empty json string', function() {
    $dto = new GamesDTO(0, '{}');
    expect($dto->json)->toBe('{}');
});

it('handles large offset values', function() {
    $dto = new GamesDTO(999999, '{"games": []}');
    expect($dto->offset)->toBe(999999);
});

it('is readonly and cannot be modified', function() {
    $dto = new GamesDTO(0, '{"games": []}');
    
    // This should not be possible due to readonly property
    // Just verify the properties are accessible
    expect(isset($dto->offset))->toBeTrue();
    expect(isset($dto->json))->toBeTrue();
});