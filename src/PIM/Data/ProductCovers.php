<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Data;

use Xwero\IgdbGameshop\PIM\DTO\DatabaseCoverDTOCollection;

class ProductCovers extends Table
{
    protected string $tableName = 'pim_product_covers';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function multiInsert(DatabaseCoverDTOCollection $coversData): int
    {
        if ($coversData->isEmpty()) {
            return 0;
        }

        $values = [];
        $params = [];
        $date = date('Y-m-d H:i:s');
        $productIds = [];
        
        foreach ($coversData->toArray() as $cover) {
            $productId = $cover->productId;
            $productIds[] = $productId;
            $url = $cover->url;
            $width = $cover->width;
            $height = $cover->height;
            
            $values[] = '(?, ?, ?, ?, ?, ?)';
            $params = array_merge($params, [$productId, $url, $width, $height, $date, $date]);
        }

        $sql = 'INSERT INTO pim_product_covers (product_id, url, width, height, created_at, updated_at) VALUES ' . implode(', ', $values);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // return insert count
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $sql = "SELECT COUNT(id) FROM pim_product_covers WHERE product_id IN ($placeholders)";


        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($productIds);

        return (int) $stmt->fetchColumn();
    }
}