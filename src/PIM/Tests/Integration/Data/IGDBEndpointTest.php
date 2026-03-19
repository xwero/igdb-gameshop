<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Integration;

use Xwero\IgdbGameshop\PIM\Data\IGDBEndpoint;
use Xwero\IgdbGameshop\Shared\Data\IGDBOAuth;

class IGDBSomeEndpoint extends IGDBEndpoint {
    public function validAccessToken(string $twitchId) {
        return $this->ValidAccessTokenCheck($this->accessToken, $twitchId);
    }
}

beforeEach(function () {
    if (!secretsFileExists()) {
        $this->markTestSkipped('no secrets for integration tests.');

    }else {
        $this->secrets = getSecrets();
        $this->accessToken = new IGDBOAuth($this->secrets['twitchId'], $this->secrets['oauthSecret'])->getAccessToken();
    }
});

it('has a valid access token', function () {
    $endpoint = new IGDBSomeEndpoint($this->accessToken);

    expect($endpoint->validAccessToken($this->secrets['twitchId']))->toBeTrue();
});