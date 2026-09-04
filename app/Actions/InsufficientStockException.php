<?php

namespace App\Actions;

use RuntimeException;

class InsufficientStockException extends RuntimeException
{
    public function __construct(public readonly int $productId)
    {
        parent::__construct("Stock insuffisant pour le produit #{$productId}.");
    }
}
