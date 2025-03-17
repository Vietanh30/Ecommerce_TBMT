<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_product_can_be_created(){
        {
            $product = Product::factory()->create();

            $this->assertDatabaseHas('products', [
                'slug' => $product->slug,
                'title' => $product->title,
            ]);
        }
    }
        public function test_product_can_be_updated()
        {
            $product = Product::factory()->create();

            $product->title = 'Updated Title';
            $product->save();

            $this->assertDatabaseHas('products', [
                'id' => $product->id,
                'title' => 'Updated Title',
            ]);
        }

        public function test_product_can_be_deleted()
        {
            $product = Product::factory()->create();

            $product->delete();

            $this->assertDatabaseMissing('products', ['id' => $product->id]);
        }
    }
