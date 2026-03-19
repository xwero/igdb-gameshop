<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Data;

abstract class IGDBEndpoint
{
    protected $baseUrl = 'https://api.igdb.com/v4/';

    protected string $accessToken;

    public function __construct(string $accessToken)
    {
        if(strlen($accessToken) == 0){
            throw new \InvalidArgumentException("The access token must not be empty.");
        }

        $this->accessToken = $accessToken;
    }

    protected function ValidAccessTokenCheck(string $accessToken, string $twitchId): bool
    {
        $url = $this->baseUrl . 'games';
        $body = 'fields id; limit 1;';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Client-ID: ' . $twitchId,
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: text/plain'
        ]);

        $resp = curl_exec($ch);

        if ($resp === false) {
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200) {
            return false;
        }

        return true;
    }
}