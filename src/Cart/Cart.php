<?php
declare(strict_types=1);

namespace Recruitment\Cart;

use Recruitment\Entity\Product;
use Recruitment\Entity\Order;

class Cart
{
    private $items = [];
    
    private function createItem($product, $quantity): Item
    {
        return new Item($product, $quantity);
    }
    
    public function addProduct(Product $product, int $quantity = 1): Cart
    {
        $item = $this->getItemByProductId($product->getId());
        
        if (empty($item)) {
            $this->items[] = $this->createItem($product, $quantity);
        } else {
            $item->setQuantity($item->getQuantity() + $quantity);
        }
        
        return $this;
    }
    
    public function setQuantity(Product $product, int $quantity): Cart
    {
        $item = $this->getItemByProductId($product->getId());
        
        if (empty($item)) {
            $this->addProduct($product, $quantity);
        } else {
            $item->setQuantity($quantity);
        }
        return $this;
    }
    
    public function removeProduct(Product $product): bool
    {
        $result = false;
        
        if (!empty($product->getId())) {
            foreach ($this->items as $index => $item) {
                if ($item->getProduct()->getId() == $product->getId()) {
                    array_splice($this->items, $index, 1);
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }
    
    public function getItems(): array
    {
        return $this->items;
    }
    
    public function getItem(int $index): Item
    {
        if (!isset($this->items[$index])) {
            throw new \OutOfBoundsException('Non Existent Item');
        }

        return $this->items[$index];
    }
    
    public function getTotalPrice(): float
    {
        $result = 0;
        foreach ($this->items as $item) {
            $result += $item->getTotalPrice();
        }
        return $result;
    }
    
    public function getTotalPriceGross(): int
    {
        $result = 0;
        foreach ($this->items as $item) {
            $result += $item->getTotalGrossPrice();
        }
        return intval($result);
    }
   
    public function checkout(int $id): Order
    {
        $order = new Order();
        $order->setId($id);
        
        foreach ($this->items as $item) {
            $order->addItem($item);
        }
        
        $this->cleanItems();
        
        return $order;
    }
    
    private function getItemByProductId(int $id):? Item
    {
        $result = null;
        
        foreach ($this->items as $key => $item) {
            if ($item->getProduct()->getId() == $id) {
                $result = $item;
                break;
            }
        }
        
        return $result;
    }
    
    private function cleanItems(): Cart
    {
        $this->items = [];
        
        return $this;
    }
}
