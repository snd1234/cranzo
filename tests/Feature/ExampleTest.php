<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedStorefrontTables();
    }

    public function test_the_home_page_renders_with_storefront_content(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Our solutions');
        $response->assertSee('Latest blogs');
    }

    public function test_products_page_uses_real_product_columns(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Cranzo Bike Body Cover');
    }

    public function test_product_detail_page_can_render_without_slug_column(): void
    {
        $response = $this->get('/product-detail/1');

        $response->assertStatus(200);
        $response->assertSee('Cranzo Bike Body Cover');
    }

    public function test_contact_form_endpoint_persists_submission(): void
    {
        $response = $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->postJson('/ajax/contact-us', [
                'full_name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'message' => 'Hello there',
            ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('contact_us', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    public function test_newsletter_form_endpoint_persists_submission(): void
    {
        $response = $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->postJson('/ajax/subscribe-newsletter', [
                'full_name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'contact_number' => '0987654321',
                'organisantion' => 'Cranzo',
            ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('enquiry', [
            'email' => 'jane@example.com',
            'full_name' => 'Jane Doe',
        ]);
    }

    private function seedStorefrontTables(): void
    {
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('category');
        Schema::dropIfExists('sub_category');
        Schema::dropIfExists('product');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('contact_us');
        Schema::dropIfExists('enquiry');

        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('sub_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->string('product_title');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('image_path')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('enquiry', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('full_name');
            $table->string('mobile_number')->nullable();
            $table->string('designation')->nullable();
            $table->string('organization')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->text('remarks')->nullable();
            $table->string('source')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        DB::table('blogs')->insert([
            [
                'title' => 'Latest blog story',
                'slug' => 'latest-blog-story',
                'short_description' => 'Short description',
                'content' => 'Content body',
                'type' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $categoryId = DB::table('category')->insertGetId([
            'name' => 'Analytical Solutions',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $subcategoryId = DB::table('sub_category')->insertGetId([
            'category_id' => $categoryId,
            'name' => 'Chromatography',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $productId = DB::table('product')->insertGetId([
            'category_id' => $categoryId,
            'sub_category_id' => $subcategoryId,
            'product_title' => 'Cranzo Bike Body Cover',
            'description' => 'A durable cover for bikes.',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('product_images')->insert([
            'product_id' => $productId,
            'image_path' => 'sample.jpg',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
