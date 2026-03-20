<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Data;

use PDO;
use PDOException;

abstract class Table
{
    protected PDO $pdo;
    protected string $tableName;
    
    public function __construct()
    {
        $dsn = $_ENV['DATABASE_DSN'] ?? getenv('DATABASE_DSN');
        if (empty($dsn)) {
            throw new \RuntimeException('DATABASE_DSN environment variable is not set');
        }
        
        try {
            $this->pdo = new PDO($dsn);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new \RuntimeException('Failed to connect to database: ' . $e->getMessage());
        }
    }

    public function clear(): void
    {
        $this->pdo->exec('TRUNCATE TABLE pim_products');
    }
}