<?php

namespace Tests\Data;

use DuncanMcClean\SimpleCommerce\Facades\Order;
use DuncanMcClean\SimpleCommerce\Orders\LineItem;
use DuncanMcClean\SimpleCommerce\Orders\OrderStatus;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderTest extends TestCase
{
    #[Test]
    public function can_get_status()
    {
        // TODO: PendingPayment & Completed states.

        $order = Order::make()->set('is_cancelled', true);
        $this->assertEquals(OrderStatus::Cancelled, $order->status());

        $order = Order::make();
        $this->assertEquals(OrderStatus::Pending, $order->status());
    }

    #[Test]
    public function can_add_line_item()
    {
        $this->markTestIncomplete('Need to implement the product repositories before this test can be completed.');

        $order = Order::make();

        $order->lineItems()->create([
            'product' => 'product-id',
            'variant' => 'variant-id',
            'quantity' => 2,
            'total' => 1000,
            'foo' => 'bar',
            'baz' => 'qux',
        ]);

        $this->assertCount(1, $order->lineItems());

        $lineItem = $order->lineItems()->first();

        $this->assertInstanceOf(LineItem::class, $lineItem);
        $this->assertNotNull($lineItem->id());
//        $this->assertEquals('product-id', $lineItem->product()->id());
//        $this->assertEquals('variant-id', $lineItem->variant()->id());
        $this->assertEquals(2, $lineItem->quantity());
        $this->assertEquals(1000, $lineItem->total());
        $this->assertEquals('bar', $lineItem->data()->get('foo'));
        $this->assertEquals('qux', $lineItem->data()->get('baz'));
    }

    #[Test]
    public function can_update_line_item()
    {
        $this->markTestIncomplete('Need to implement the product repositories before this test can be completed.');

        $order = Order::make()->lineItems([
            [
                'id' => 'abc123',
                'product' => 'product-id',
                'variant' => 'variant-id',
                'quantity' => 2,
                'total' => 1000,
                'foo' => 'bar',
                'baz' => 'qux',
            ],
            [
                'id' => 'def456',
                'product' => 'another-product-id',
                'quantity' => 1,
                'total' => 2500,
                'bar' => 'baz',
            ]
        ]);

        $order->lineItems()->update('abc123', [
            'product' => 'product-id',
            'variant' => 'variant-id',
            'quantity' => 1, // This changed...
            'total' => 500, // This changed too...
            'barz' => 'foo', // This is new...
            // And, some other keys were removed...
        ]);

        $lineItem = $order->lineItems()->find('abc123');

        $this->assertEquals(1, $lineItem->quantity());
        $this->assertEquals(500, $lineItem->total());
        $this->assertNull($lineItem->data()->get('foo'));
        $this->assertNull($lineItem->data()->get('baz'));
        $this->assertEquals('foo', $lineItem->data()->get('barz'));
    }

    #[Test]
    public function can_remove_line_item()
    {
        $order = Order::make()->lineItems([
            [
                'id' => 'abc123',
                'product' => 'product-id',
                'variant' => 'variant-id',
                'quantity' => 2,
                'total' => 1000,
                'foo' => 'bar',
                'baz' => 'qux',
            ],
            [
                'id' => 'def456',
                'product' => 'another-product-id',
                'quantity' => 1,
                'total' => 2500,
                'bar' => 'baz',
            ]
        ]);

        $this->assertCount(2, $order->lineItems());

        $order->lineItems()->remove('abc123');

        $this->assertCount(1, $order->lineItems());
        $this->assertNull($order->lineItems()->find('abc123'));
        $this->assertNotNull($order->lineItems()->find('def456'));
    }

    #[Test]
    public function can_build_path()
    {
        $order = Order::make()
            ->orderNumber(1234)
            ->date(Carbon::parse('2024-01-01 10:35:10'));

        $this->assertEquals(
            base_path('content/orders/2024-01-01-103510.1234.yaml'),
            $order->buildPath()
        );
    }
}