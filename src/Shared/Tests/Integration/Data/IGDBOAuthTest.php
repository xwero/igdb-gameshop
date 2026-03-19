<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\Shared\Tests\Unit;

use Xwero\IgdbGameshop\Shared\Data\IGDBOAuth;

beforeEach(function () {
    if (!secretsFileExists()) {
        $this->markTestSkipped('no secrets for integration tests.');
    }
});

it('returns string when credentials are valid', function () {
    $secrets = getSecrets();
    $oauth = new IGDBOAuth($secrets['twitchId'], $secrets['oauthSecret']);
    $result = $oauth->getAccessToken();

    expect($result)->toBeString();
});


