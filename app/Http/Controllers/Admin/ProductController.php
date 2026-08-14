<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.form', ['product' => new Product(), 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }
        $product = Product::create($data);
        $this->syncMedia($request, $product);

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'videos']);
        $categories = Category::orderBy('name')->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);
        if ($request->filled('slug')) {
            $data['slug'] = $this->uniqueSlug($request->slug, $product->id);
        }
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }
        $product->update($data);
        $this->syncMedia($request, $product);

        return back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function deleteImage(ProductImage $image)
    {
        $image->delete();
        return back()->with('success', 'Image removed.');
    }

    public function deleteVideo(ProductVideo $video)
    {
        $video->delete();
        return back()->with('success', 'Video removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:200',
            'sku' => 'nullable|string|max:80',
            'size' => 'nullable|string|max:80',
            'short_description' => 'nullable|string|max:300',
            'description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'how_to_use' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'badge' => 'nullable|string|max:50',
            'amazon_url' => 'nullable|url|max:500',
            'stock' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|image|max:5120',
            'images.*' => 'nullable|image|max:5120',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/webm,video/quicktime|max:51200',
        ]);

        $data['cashback'] = $request->boolean('cashback');
        $data['is_best_seller'] = $request->boolean('is_best_seller');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new'] = $request->boolean('is_new');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['discount'] = $data['discount'] ?? 0;
        $data['rating'] = $data['rating'] ?? 0;
        $data['reviews_count'] = $data['reviews_count'] ?? 0;
        $data['stock'] = $data['stock'] ?? 0;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function syncMedia(Request $request, Product $product): void
    {
        if ($request->hasFile('images')) {
            $sort = (int) $product->images()->max('sort_order');
            foreach ($request->file('images') as $file) {
                $sort++;
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $file->store('products', 'public'),
                    'alt' => $product->name,
                    'sort_order' => $sort,
                ]);
            }
        }

        if ($request->hasFile('videos')) {
            $sort = (int) $product->videos()->max('sort_order');
            foreach ($request->file('videos') as $file) {
                $sort++;
                ProductVideo::create([
                    'product_id' => $product->id,
                    'path' => $file->store('videos', 'public'),
                    'title' => $product->name . ' video',
                    'sort_order' => $sort,
                ]);
            }
        }

        if (!$product->thumbnail) {
            $first = $product->images()->first();
            if ($first) {
                $product->update(['thumbnail' => $first->path]);
            }
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $i = 1;
        while (Product::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
