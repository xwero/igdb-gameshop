<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Data;

use PDO;

class Products extends Table
{
    protected string $tableName = 'pim_products';
    
    public function __construct()
    {
        parent::__construct();
    }

    private function checkIgdbIds(array $igdbIds): array
    {
        $placeholders = implode(',', array_fill(0, count($igdbIds), '?'));

        $sql = "SELECT id FROM $this->tableName WHERE id IN ($placeholders)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($igdbIds);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function multiInsert(array $productsData): array
    {
        if (empty($productsData)) {
            return [];
        }
        // prevent duplicate inserts
        $checkIds = array_column($productsData, 'id');
        $existingIds = $this->checkIgdbIds($checkIds);

        if(count($existingIds) > 0) {
            $productsData = array_filter($productsData, fn($product) => !in_array($product['id'], $existingIds));
        }
        
        $values = [];
        $params = [];
        $igdbIds = [];

        foreach ($productsData as $product) {
            $igdbId = $product['id'];
            $igdbIds[] = $igdbId;
            $name = $product['name'];
            $date = date('Y-m-d H:i:s');
            
            $values[] = '(?, ?, ?, ?)';
            $params = array_merge($params, [$igdbId, $name, $date, $date]);
        }

        $sql = 'INSERT INTO pim_products (igdb_id, name, created_at, updated_at) VALUES ' . implode(', ', $values);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        // Get the inserted IDs
        $placeholders = implode(',', array_fill(0, count($igdbIds), '?'));
        $sql = "SELECT id, igdb_id FROM pim_products WHERE igdb_id IN ($placeholders)";


        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($igdbIds);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}