<?php

declare(strict_types=1);

namespace Recruitment\Tests\Cart;

use PHPUnit\Framework\TestCase;
use Recruitment\Cart\Cart;
use Recruitment\Entity\Order;
use Recruitment\Entity\Product;

class CartTest extends TestCase
{
    /**
     * @test
     */
    public function itAddsOneProduct(): void
    {
        $product = $this->buildTestProduct(1, 15000);

        $cart = new Cart();
        $cart->addProduct($product, 1);

        $this->assertCount(1, $cart->getItems());
        $this->assertEquals(15000, $cart->getTotalPrice());
        $this->assertEquals($product, $cart->getItem(0)->getProduct());
    }

    /**
     * @test
     */
    public function itRemovesExistingProduct(): void
    {
        $product1 = $this->buildTestProduct(1, 15000);
        $product2 = $this->buildTestProduct(2, 10000);

        $cart = new Cart();
        $cart->addProduct($product1, 1)
            ->addProduct($product2, 1);
        $cart->removeProduct($product1);

        $this->assertCount(1, $cart->getItems());
        $this->assertEquals(10000, $cart->getTotalPrice());
        $this->assertEquals($product2, $cart->getItem(0)->getProduct());
    }

    /**
     * @test
     */
    public function itIncreasesQuantityWhenAddingAnExistingProduct(): void
    {
        $product = $this->buildTestProduct(1, 15000);

        $cart = new Cart();
        $cart->addProduct($product, 1)
            ->addProduct($product, 2);

        $this->assertCount(1, $cart->getItems());
        $this->assertEquals(45000, $cart->getTotalPrice());
    }

    /**
     * @test
     */
    public function itUpdatesQuantityOfAnExistingItem(): void
    {
        $product = $this->buildTestProduct(1, 15000);

        $cart = new Cart();
        $cart->addProduct($product, 1)
            ->setQuantity($product, 2);

        $this->assertEquals(30000, $cart->getTotalPrice());
        $this->assertEquals(2, $cart->getItem(0)->getQuantity());
    }

    /**
     * @test
     */
    public function itAddsANewItemWhileSettingQuantityForNonExistentItem(): void
    {
        $product = $this->buildTestProduct(1, 15000);

        $cart = new Cart();
        $cart->setQuantity($product, 1);

        $this->assertEquals(15000, $cart->getTotalPrice());
        $this->assertCount(1, $cart->getItems());
    }

    /**
     * @test
     * @dataProvider getNonExistentItemIndexes
     * @expectedException \OutOfBoundsException
     */
    public function itThrowsExceptionWhileGettingNonExistentItem(int $index): void
    {
        $product = $this->buildTestProduct(1, 15000);

        $cart = new Cart();
        $cart->addProduct($product, 1);
        $cart->getItem($index);
    }

    /**
     * @test
     */
    public function removingNonExistentItemDoesNotRaiseException(): void
    {
        $cart = new Cart();
        $cart->addProduct($this->buildTestProduct(1, 15000));
        $cart->removeProduct(new Product());

        $this->assertCount(1, $cart->getItems());
    }

    /**
     * @test
     */
    public function itClearsCartAfterCheckout(): void
    {
        $items = [
            ['id' => 1, 'tax' => '0%',  'quantity' => 2, 'total_price' => 14000, 'total_gross_price' => 14000],
            ['id' => 2, 'tax' => '0%',  'quantity' => 3, 'total_price' => 9750, 'total_gross_price' => 9750],
            
            ['id' => 3, 'tax' => '5%', 'quantity' => 2, 'total_price' => 80000, 'total_gross_price' => 84000],
            ['id' => 4, 'tax' => '5%', 'quantity' => 3, 'total_price' => 93300, 'total_gross_price' => 97965],
            
            ['id' => 5, 'tax' => '8%', 'quantity' => 3, 'total_price' => 60000, 'total_gross_price' => 64800],
            ['id' => 6, 'tax' => '8%', 'quantity' => 4, 'total_price' => 50000, 'total_gross_price' => 54000],
            
            ['id' => 7, 'tax' => '23%', 'quantity' => 2, 'total_price' => 40000, 'total_gross_price' => 49200],
            ['id' => 8, 'tax' => '23%', 'quantity' => 6, 'total_price' => 30000, 'total_gross_price' => 36900],
        ];
        
        $cart = new Cart();
        foreach ($items as $item) {
            $cart->addProduct($this->buildTestProduct($item['id'], intval($item['total_price'] / $item['quantity']), $item['tax']), $item['quantity']);
        }
        
        $order = $cart->checkout(7);

        $this->assertCount(0, $cart->getItems());
        $this->assertEquals(0, $cart->getTotalPrice());
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(377050, $order->getDataForView()['total_price']);

        $this->assertEquals(['id' => 7, 'items' => $items, 'total_price' => 377050, 'total_gross_price' => 410615], $order->getDataForView());
    }

    public function getNonExistentItemIndexes(): array
    {
        return [
            [PHP_INT_MIN],
            [-1],
            [1],
            [PHP_INT_MAX],
        ];
    }

    private function buildTestProduct(int $id, int $price, string $tax = '23%'): Product
    {
        return (new Product())->setId($id)->setUnitPrice($price)->setTax($tax);
    }
}
