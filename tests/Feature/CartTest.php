<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $overrides = []): Product
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description',
        ]);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'A test product',
            'price' => 15000000,
            'stock' => 10,
            'brand' => 'TestBrand',
        ], $overrides));
    }

    /** @test */
    public function cart_page_renders(): void
    {
        $response = $this->get('/cart');
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_can_add_item_to_cart(): void
    {
        $product = $this->createProduct();

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    /** @test */
    public function authenticated_user_can_add_item_to_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    /** @test */
    public function cannot_add_more_than_available_stock(): void
    {
        $product = $this->createProduct(['stock' => 3]);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        $response->assertSessionHasErrors('quantity');
    }

    /** @test */
    public function cannot_add_nonexistent_product(): void
    {
        $response = $this->post('/cart/add', [
            'product_id' => 9999,
            'quantity' => 1,
        ]);

        $response->assertSessionHasErrors('product_id');
    }

    /** @test */
    public function can_update_cart_item_quantity(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        // First add something
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cartItem = \App\Models\CartItem::first();

        $response = $this->actingAs($user)->patch("/cart/{$cartItem->id}", [
            'quantity' => 5,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 5,
        ]);
    }

    /** @test */
    public function can_remove_cart_item(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cartItem = \App\Models\CartItem::first();

        $response = $this->actingAs($user)->delete("/cart/{$cartItem->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }
}
