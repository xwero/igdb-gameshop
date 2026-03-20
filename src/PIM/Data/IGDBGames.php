<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Data;

use Xwero\IgdbGameshop\PIM\DTO\GamesDTO;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTOCollection;

class IGDBGames extends IGDBEndpoint
{
    public function fetchMultipleGames(string $twitchId, int $limit, int $startOffset, int $maxRequests): GamesDTOCollection
    {
        if($this->ValidAccessTokenCheck($this->accessToken, $twitchId) == false){
            return new GamesDTOCollection();
        }

        $responses = [];
        $mh = curl_multi_init();
        $handles = [];
        
        for ($i = 0; $i < $maxRequests; $i++) {
            $offset = $startOffset + ($i * $limit);
            $url = $this->baseUrl . 'games';
            $body = 'fields *; limit ' . $limit . '; offset ' . $offset . ';';
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Client-ID: ' . $twitchId,
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: text/plain'
            ]);
            
            curl_multi_add_handle($mh, $ch);
            $handles[$offset] = $ch;
        }
        
        $running = null;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);
        
        foreach ($handles as $offset => $handle) {
            $result = curl_multi_getcontent($handle);
            if (is_string($result) && json_validate($result)) {
                $responses[] = new GamesDTO($offset, $result);
            }
            curl_multi_remove_handle($mh, $handle);
        }
        
        curl_multi_close($mh);
        
        return count($responses) == 0 ? new GamesDTOCollection() : new GamesDTOCollection(...$responses);
    }

    public function fetchMultipleGames2(string $twitchId, int $limit, int $startOffset, int $maxRequests): GamesDTOCollection
    {
        if($this->ValidAccessTokenCheck($this->accessToken, $twitchId) == false){
            return new GamesDTOCollection();
        }

        $responses = [];
        $mh = curl_multi_init();
        $handles = [];

        for ($i = 0; $i < $maxRequests; $i++) {
            $offset = $startOffset + ($i * $limit);
            $url = $this->baseUrl . 'games';
            $body = 'fields id, name, cover.*; 
                limit ' . $limit . '; 
                offset ' . $offset . ';'
            ;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Client-ID: ' . $twitchId,
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: text/plain'
            ]);

            curl_multi_add_handle($mh, $ch);
            $handles[$offset] = $ch;
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        foreach ($handles as $offset => $handle) {
            $result = curl_multi_getcontent($handle);
            if (is_string($result) && json_validate($result)) {
                $responses[] = new GamesDTO($offset, $result);
            }
            curl_multi_remove_handle($mh, $handle);
        }

        curl_multi_close($mh);

        return count($responses) == 0 ? new GamesDTOCollection() : new GamesDTOCollection(...$responses);
    }
}