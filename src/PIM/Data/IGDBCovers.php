<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Data;

use Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTOCollection;
use Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTO;

class IGDBCovers extends IGDBEndpoint
{

    public function fetchMultipleCovers(string $twitchId, GameCoversRequestDTOCollection $requests): GameCoversResponseDTOCollection
    {
        if($this->ValidAccessTokenCheck($this->accessToken, $twitchId) == false){
            return new GameCoversResponseDTOCollection();
        }

        $responses = [];
        $mh = curl_multi_init();
        $handles = [];

        foreach ($requests->toArray() as $request) {
            $gameIds = implode(',', array_filter($request->games));
            if (empty($gameIds)) {
                continue;
            }

            $url = $this->baseUrl . 'covers';
            $body = 'fields game,url,width,height; where game = (' . $gameIds . ');';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Client-ID: '. $twitchId,
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: text/plain'
            ]);

            curl_multi_add_handle($mh, $ch);
            $handles[$request->offset] = $ch;
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        foreach ($handles as $offset => $handle) {
            $result = curl_multi_getcontent($handle);
            if (is_string($result) && json_validate($result)) {
                $coversData = json_decode($result, true);
                $covers = [];
                
                foreach ($coversData as $coverData) {
                    $covers[] = [
                        'gameId' => $coverData['game'] ?? 0,
                        'url' => $coverData['url'] ?? '',
                        'width' => $coverData['width'] ?? 0,
                        'height' => $coverData['height'] ?? 0,
                    ];
                }

                if(count($covers) > 0) {
                    $responses[] = new GameCoversResponseDTO($offset, $covers);
                }
            }
            curl_multi_remove_handle($mh, $handle);
        }

        curl_multi_close($mh);

        return count($responses) == 0 ? new GameCoversResponseDTOCollection() : new GameCoversResponseDTOCollection(...$responses);
    }
}