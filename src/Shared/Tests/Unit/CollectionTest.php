<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\Shared\Tests\Unit;

use Xwero\IgdbGameshop\Shared\Collection;

class TestCollection extends Collection
{
    public function addItem(mixed $item): void
    {
        $this->items[] = $item;
    }
}

it('is empty when newly created', function() {
    $collection = new TestCollection();
    expect($collection->isEmpty())->toBeTrue();
});

it('is not empty when items are added', function() {
    $collection = new TestCollection();
    $collection->addItem('test');
    expect($collection->isEmpty())->toBeFalse();
});

it('returns empty array when toArray is called on empty collection', function() {
    $collection = new TestCollection();
    expect($collection->toArray())->toBe([]);
});

it('returns array with items when toArray is called', function() {
    $collection = new TestCollection();
    $collection->addItem('item1');
    $collection->addItem('item2');
    
    expect($collection->toArray())->toBe(['item1', 'item2']);
});

it('can handle different types of items', function() {
    $collection = new TestCollection();
    $collection->addItem(1);
    $collection->addItem('string');
    $collection->addItem(['nested' => 'array']);
    
    expect($collection->toArray())->toBe([1, 'string', ['nested' => 'array']]);
});