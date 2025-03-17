<?php
namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $cart = Cart::factory()->for($user)->withItems([$product])->create();

        // Simulate a POST request to the store method
        $response = $this->actingAs($user)->post(route('orders.store'), [
            // ... other order data
        ]);

        $response->assertStatus(302) // Redirect to success page
            ->assertSessionHas('success', 'Order created successfully');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'cart_id' => $cart->id,
        ]);
    }

    public function test_cannot_create_order_with_empty_cart()
    {
        $user = User::factory()->create();

        // Create a cart without any items
        $cart = Cart::factory()->for($user)->create();

        $response = $this->actingAs($user)->post(route('orders.store'), [
            // ... other order data
        ]);

        $response->assertSessionHasErrors('cart');
    }
}




