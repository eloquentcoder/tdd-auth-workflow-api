<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::latest()->take(10)->get();
        $featured_products = Product::latest()->featured()->take(5)->get();
        $categories = Category::all();
        return response()->json([
            'events' => EventResource::collection($events),
            'featured_products' => ProductResource::collection($featured_products),
            'categories' => CategoryResource::collection($categories),
            'message' => 'Data fetched successfully',
            'success' => true
        ]);
    }
}
