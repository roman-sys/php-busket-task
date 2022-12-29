<?php

declare(strict_types=1);

namespace Recruitment\Entity;

use Recruitment\Entity\Exception\InvalidUnitPriceException;

class Product
{
    private $id;
    
    private $name;
    
    private $unitPrice;
    
    private $minimumQuantity = 1;
    
    private $tax;
    
    private $taxRules = [
        '0%' => 1,
        '5%' => 1.05,
        '8%' => 1.08,
        '23%' => 1.23,
    ];
    
    public function getId():? int
    {
        return $this->id;
    }
    
    public function setId(int $id): Product
    {
        $this->id = $id;
        return $this;
    }
    
    public function setName(string $name): Product
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getTaxMultiplier(): float
    {
        return $this->taxRules[$this->tax];
    }
    
    public function setTax(string $tax): Product
    {
        $this->tax = $tax;
        return $this;
    }

    public function getTax():? string
    {
        return $this->tax;
    }
    
    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(int $unitPrice): Product
    {
        if ($unitPrice <= 0) {
            throw new InvalidUnitPriceException();
        }
        
        $this->unitPrice = $unitPrice;
        return $this;
    }
    
    public function setMinimumQuantity(int $minimumQuantity): Product
    {
        if ($minimumQuantity < 1) {
            throw new \InvalidArgumentException('Invalid minimum quantity');
        }
        
        $this->minimumQuantity = $minimumQuantity;
        
        return $this;
    }
    
    public function getMinimumQuantity(): int
    {
        return $this->minimumQuantity;
    }
}
