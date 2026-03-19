<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\Data\IGDBGames;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTOCollection;
use Xwero\IgdbGameshop\Shared\Data\IGDBOAuth;

beforeEach(function () {
    if (!secretsFileExists()) {
        $this->markTestSkipped('no secrets for integration tests.');

    }else {
        $this->secrets = getSecrets();
        $this->accessToken = new IGDBOAuth($this->secrets['twitchId'], $this->secrets['oauthSecret'])->getAccessToken();
    }
});

it('returns small batch of games', function() {
    $games = new IGDBGames($this->accessToken);
    $result = $games->fetchMultipleGames($this->secrets['twitchId'], 10, 0, 1);

    expect($result->isEmpty())->toBeFalse();
    $content = $result->toArray()[0]->json;
    expect(json_validate($content))->toBeTrue();
    $jsonArray = json_decode($content, true);
    expect(count($jsonArray))->toBe(10);
});

it('returns two API responses', function() {
    $games = new IGDBGames($this->accessToken);
    $result = $games->fetchMultipleGames($this->secrets['twitchId'], 10, 0, 2);

    $collection = $result->toArray();
    expect(count($collection))->toBe(2);
});