<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function setupCheckout(): array
    {
        $user = User::factory()->create(['role' => 'customer']);

        $category = Category::create([
            'name' => 'Laptops',
            'slug' => 'laptops',
            'description' => 'Laptop computers',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Laptop',
            'slug' => 'test-laptop',
            'description' => 'A test laptop',
            'price' => 15000000,
            'stock' => 10,
            'brand' => 'TestBrand',
        ]);

        // Add to cart
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        return [
            'user' => $user,
            'product' => $product,
        ];
    }

    /** @test */
    public function checkout_page_requires_authentication(): void
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_view_checkout(): void
    {
        $setup = $this->setupCheckout();

        $response = $this->actingAs($setup['user'])->get('/checkout');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_complete_checkout(): void
    {
        $setup = $this->setupCheckout();
        $product = $setup['product'];

        $response = $this->actingAs($setup['user'])->post('/checkout', [
            'shipping_name' => 'John Doe',
            'shipping_phone' => '081234567890',
            'shipping_address' => 'Jl. Tech No. 1',
            'shipping_city' => 'Jakarta',
            'shipping_postal_code' => '12345',
            'payment_method' => 'bank_transfer',
            'notes' => 'Please handle with care',
        ]);

        // Should redirect to confirmation page
        $response->assertRedirect();

        // Order should exist in DB
        $this->assertDatabaseHas('orders', [
            'user_id' => $setup['user']->id,
            'shipping_name' => 'John Doe',
            'shipping_city' => 'Jakarta',
            'payment_method' => 'bank_transfer',
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);

        // Order items should exist
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'product_name' => 'Test Laptop',
            'quantity' => 2,
            'product_price' => 15000000,
        ]);

        // Stock should be decremented
        $product->refresh();
        $this->assertEquals(8, $product->stock);

        // Cart should be empty after checkout
        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $product->id,
        ]);
    }

    /** @test */
    public function checkout_validates_required_fields(): void
    {
        $setup = $this->setupCheckout();

        $response = $this->actingAs($setup['user'])->post('/checkout', []);

        $response->assertSessionHasErrors([
            'shipping_name',
            'shipping_phone',
            'shipping_address',
            'shipping_city',
            'shipping_postal_code',
            'payment_method',
        ]);
    }

    /** @test */
    public function checkout_fails_with_empty_cart(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/checkout', [
            'shipping_name' => 'John',
            'shipping_phone' => '08123456',
            'shipping_address' => 'Address',
            'shipping_city' => 'City',
            'shipping_postal_code' => '12345',
            'payment_method' => 'bank_transfer',
        ]);

        // Should redirect back with error
        $response->assertRedirect();
    }

    /** @test */
    public function checkout_prevents_ordering_more_than_stock(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'description' => 'Test',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Low Stock Item',
            'slug' => 'low-stock-item',
            'description' => 'Almost gone',
            'price' => 1000000,
            'stock' => 1,
            'brand' => 'Test',
        ]);

        // Add 1 item (which is all the stock)
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Manually set stock to 0 to simulate race condition
        $product->update(['stock' => 0]);

        $response = $this->actingAs($user)->post('/checkout', [
            'shipping_name' => 'John',
            'shipping_phone' => '08123456',
            'shipping_address' => 'Address',
            'shipping_city' => 'City',
            'shipping_postal_code' => '12345',
            'payment_method' => 'bank_transfer',
        ]);

        // Should fail — stock is 0
        $response->assertRedirect();

        // No order should be created
        $this->assertDatabaseCount('orders', 0);

        // Stock should still be 0 (transaction rolled back)
        $product->refresh();
        $this->assertEquals(0, $product->stock);
    }
}
