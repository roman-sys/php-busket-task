<?php

declare(strict_types=1);

namespace Recruitment\Cart;

use Recruitment\Entity\Product;
use Recruitment\Cart\Exception\QuantityTooLowException;

class Item
{
    private $product;
    private $quantity;
    
    public function __construct(Product $product, int $quantity = 1)
    {
        $this->product = $product;

        if ($quantity < $this->product->getMinimumQuantity()) {
            throw new \InvalidArgumentException('Quantity is too low.');
        }
        
        $this->quantity = $quantity;
    }
    
    public function getProduct(): Product
    {
        return $this->product;
    }
    
    public function getProductId():? int
    {
        return $this->getProduct()->getId();
    }
    
    public function getProductTax():? string
    {
        return $this->getProduct()->getTax();
    }
    
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    
    public function getTotalPrice(): int
    {
        return intval($this->getQuantity() * $this->getProduct()->getUnitPrice());
    }
    
    public function getTotalGrossPrice(): int
    {
        return intval(round($this->getTotalPrice() * $this->getProduct()->getTaxMultiplier()));
    }
    
    public function setQuantity(int $quantity): Item
    {
        
        if ($quantity < $this->product->getMinimumQuantity()) {
            throw new QuantityTooLowException('Quantity is too low.');
        }
        
        $this->quantity = $quantity;
        return $this;
    }
}
