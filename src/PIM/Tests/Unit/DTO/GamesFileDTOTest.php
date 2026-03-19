<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\DTO\GamesFileDTO;

it('constructs with valid offset and content', function() {
    $dto = new GamesFileDTO(0, ['game1', 'game2']);
    
    expect($dto)->toBeInstanceOf(GamesFileDTO::class);
    expect($dto->offset)->toBe(0);
    expect($dto->content)->toBe(['game1', 'game2']);
});

it('requires integer offset', function() {
    $dto = new GamesFileDTO(10, ['game1']);
    expect($dto->offset)->toBe(10);
});

it('requires array content', function() {
    $dto = new GamesFileDTO(0, ['game1', 'game2', 'game3']);
    expect($dto->content)->toBe(['game1', 'game2', 'game3']);
});

it('Throws exception when content array is empty', function() {
    $dto = new GamesFileDTO(0, []);
})->throws(\InvalidArgumentException::class);

it('handles complex content structure', function() {
    $complexContent = [
        ['id' => 1, 'name' => 'Game 1'],
        ['id' => 2, 'name' => 'Game 2']
    ];
    $dto = new GamesFileDTO(0, $complexContent);
    expect($dto->content)->toBe($complexContent);
});