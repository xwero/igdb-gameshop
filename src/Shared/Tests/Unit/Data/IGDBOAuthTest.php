<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\Shared\Tests\Unit;

use Xwero\IgdbGameshop\Shared\Data\IGDBOAuth;


it('returns null when credentials are invalid', function() {
    $oauth = new IGDBOAuth('invalid_id', 'invalid_secret');
    $result = $oauth->getAccessToken();

    expect($result)->toBeNull();
});

it('requires non-empty twitch id', function() {
    $oauth = new IGDBOAuth('', 'secret');
})->throws(\InvalidArgumentException::class);

it('requires non-empty oauth secret', function() {
    $oauth = new IGDBOAuth('id', '');
})->throws(\InvalidArgumentException::class);