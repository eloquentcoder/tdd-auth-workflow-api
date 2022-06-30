<?php

use App\Models\Category;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;


test('user can fetch recent events from api call', function () {
    $events = Event::factory()->count(10)->create();
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->getJson('/api/v1/home');
    $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has('featured_products')
                    ->has('success')
                    ->has('categories')
                    ->has('events', count($events))
                    ->has('events.0',
                        fn ($json) =>
                        $json->where('title', $events[0]->title)
                            ->where('description', $events[0]->description)
                            ->where('eventId', $events[0]->event_id)
                            ->where('price', $events[0]->price)
                            ->where('dateTime', $events[0]->date_time)
                            ->where('canGenerateTicket', $events[0]->can_generate_ticket)
                            
                    )
            );
});


test('user can fetch featured products from home api call', function () {
    $featured_products = Product::factory()
            ->count(5)
            ->for(Category::factory()->create([
                'title' => 'Ankara',
            ]))
            ->create([
        'is_featured' => true
    ]);
    
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->getJson('api/v1/home');

    $response->assertStatus(200)
             ->assertJson(
                fn (AssertableJson $json) => 
                $json->has('message')
                     ->has('categories')
                     ->has('events')
                     ->has('success')
                     ->has('featured_products', count($featured_products))
                     ->has('featured_products.0', 
                            fn($json) => 
                            $json->where('title', $featured_products[0]->title)
                                ->where('description', $featured_products[0]->description)
                                ->where('price', $featured_products[0]->price)
                                ->where('featuredImage', $featured_products[0]->featured_image)
                                ->where('productId', $featured_products[0]->product_id)
                                ->where('tags', $featured_products[0]->tags)
                                ->where('isFeatured', $featured_products[0]->is_featured)
                                ->where('otherImages', $featured_products[0]->other_images)
                                ->where('category', $featured_products[0]->category->title)
                     )                    
             );
});



test('user can fetch categories from home api', function() {
    $categories = Category::factory()->count(10)->create();

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->getJson('api/v1/home');

    $response->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) => 
                $json->has('message')
                     ->has('events')
                     ->has('success')
                     ->has('featured_products')
                     ->has('categories', 10)
                     ->has('categories.0', 
                        fn($json) => 
                            $json->where('title', $categories[0]->title)
                                 ->where('imageUrl', $categories[0]->image_url)
                                 ->where('description', $categories[0]->description)
                        )
            );

});