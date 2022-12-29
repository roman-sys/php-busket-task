<?php

declare(strict_types=1);

namespace Recruitment\Entity;

use Recruitment\Cart\Item;

class Order
{
    private $id;
    private $items = [];
    
    public function setId(int $id): Order
    {
        $this->id = $id;
        return $this;
    }
    
    public function addItem(Item $item): Order
    {
        $this->items[] = $item;
        return $this;
    }
    
    public function getDataForView(): array
    {
        $result = [
            'id' => $this->id,
            'total_price' => 0,
            'total_gross_price' => 0,
        ];
        
        foreach ($this->items as $item) {
            $totalPrice = $item->getTotalPrice();
            $totalGrossPrice =  $item->getTotalGrossPrice();
            
            $result['items'][] = [
                'id' => $item->getProductId(),
                'tax' => $item->getProductTax(),
                'quantity' => $item->getQuantity(),
                'total_price' => $totalPrice,
                'total_gross_price' => $totalGrossPrice,
            ];
            
            $result['total_price'] += $totalPrice;
            $result['total_gross_price'] += $totalGrossPrice;
        }
        return $result;
    }
}
