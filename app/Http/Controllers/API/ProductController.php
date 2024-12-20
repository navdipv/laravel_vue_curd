<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            // Building the query
            $query = Product::with('categories', 'categories.category');

            // Sorting logic
            if ($request->filled('sort_by')) {
                $sortDirection = $request->get('sort_direction', 'asc');
                $query->orderBy($request->get('sort_by'), $sortDirection);
            }

            // Searching logic
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where('name', 'like', '%' . $search . '%');
            }

            // Pagination
            $products = $query->paginate($request->get('per_page', config('constants.per_page')));

            return response()->json([
                'success' => true,
                'message' => '',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to retrieve products: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'categories' => 'required',
            'image' => 'nullable|image:jpeg,png,jpg,gif,svg|max:5000',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        try {
            if(!empty($request->id)){
                $product = Product::find($request->id);
            }else{
                $product = new Product();
            }
            $product->ulid = Str::ulid();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . Str::slug($product->name) . '.' . $file->getClientOriginalExtension();

                // Save the file to the public disk
                $path = $file->storeAs('images', $filename, 'public');  // Store in directory 'images' within storage/app/public

                // Set the path to be stored in the database as a relative path or URL
                $product->image = $path;  // Stores the path like 'images/1234567890_filename.jpg'
            }

            $product->status = $request->status ?? 1;
            $product->save();

            if(!empty($request->categories)){
                
                ProductCategory::where('product_id', $product->id)->delete();
                
                foreach (explode(',', $request->categories) as $key => $value) {
                    $ProductCategory = new ProductCategory();
                    $ProductCategory->product_id = $product->id;
                    $ProductCategory->category_id = $value;
                    $ProductCategory->save();
                }
            }
            if(!empty($request->id)){
                return $this->success([], 'Product updated successfully.', 201);
            }else{
             return $this->success([], 'Product created successfully.', 201);                
            }
        } catch (\Exception $e) {
            return $this->error('Failed to create product: ' . $e->getMessage(), 400);
        }
    }

    public function show($ulid)
    {
        try {
            $product = Product::where('ulid', $ulid)->firstOrFail();
            $product->categories = ProductCategory::where('product_id', $product->id)->pluck("category_id")->toArray();
            return $this->success($product, '', 200);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve product: ' . $e->getMessage(), 400);
        }
    }   

    public function destroy($ulid)
    {
        try {
            $product = Product::where('ulid', $ulid)->firstOrFail();
            $product->delete();

            return $this->success([], 'Product deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->error('Failed to delete product: ' . $e->getMessage(), 400);
        }
    }
}
