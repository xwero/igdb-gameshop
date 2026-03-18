<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\Shared\Data;

readonly class IGDBOAuth
{
    private string $twitchId;
    private string $oauthSecret;
    public function __construct(string $twitchId, string $oauthSecret)
    {
        if(strlen(trim($twitchId)) == 0) {
           throw new \InvalidArgumentException('Add the twitch id.');
        }

        if(strlen(trim($oauthSecret)) == 0) {
            throw new \InvalidArgumentException('Add the OAuth secret.');
        }

        $this->twitchId = $twitchId;
        $this->oauthSecret = $oauthSecret;
    }
    
    public function getAccessToken(): ?string
    {
        // IGDB OAuth endpoint
        $oauthUrl = 'https://id.twitch.tv/oauth2/token';
        
        $postData = [
            'client_id' => $this->twitchId,
            'client_secret' => $this->oauthSecret,
            'grant_type' => 'client_credentials'
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($postData)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($oauthUrl, false, $context);
        
        if ($result === false) {
            return null;
        }
        
        /** @var array{access_token?: string} $response */
        $response = json_decode($result, true);
        
        if (is_array($response) && isset($response['access_token'])) {
            return $response['access_token'];
        }
        
        return null;
    }
}