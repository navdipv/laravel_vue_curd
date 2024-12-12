<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            // Building the query
            $query = Category::query();

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

            // Including subcategories
            $query->with('parent');

            // Pagination
            $categories = $query->paginate($request->get('per_page', config('constants.per_page'))); // default to 15 items per page

            return response()->json([
                'success' => true,
                'message' => '',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to retrieve categories: ' . $e->getMessage()], 500);
        }
    }

    public function parentCategories(Request $request)
    {
        try {

            $categories = Category::where('parent_id',0);
            if($request->ulid){
                $categories = $categories->where('ulid', '!=', $request->ulid);
            }
            $categories = $categories->pluck("name", "id")->toArray();
            return $this->success($categories, '', 200);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve categories: ' . $e->getMessage(), 400);
        }
    }
    public function allCategories(){
        try {

            $categories = Category::query();
            $categories = $categories->pluck("name", "id")->toArray();
            return $this->success($categories, '', 200);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve categories: ' . $e->getMessage(), 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        try {
            $category = new Category();
            $category->ulid = Str::ulid();
            $category->name = $request->name;
            $category->parent_id = $request->parent_id ?? 0;
            $category->save();
            return $this->success([], 'Category created successfully.', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create category: ' . $e->getMessage(), 400);
        }
    }

    public function show($ulid)
    {
        try {

            $category = Category::with(['subcategories'])->where('ulid', $ulid)->firstOrFail();

            return $this->success($category, '', 200);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve category:' . $e->getMessage(), 400);
        }
    }

    public function update(Request $request, $ulid)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parent_id' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        try {
            $category = Category::where('ulid', $ulid)->firstOrFail();
            $category->name = $request->name;
            $category->parent_id = $request->parent_id ?? 0;
            $category->save();

            return $this->success([], 'Category updated successfully.', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to update category: ' . $e->getMessage(), 400);
        }
    }

    public function destroy($ulid)
    {
        try {
            $category = Category::where('ulid', $ulid)->firstOrFail();
            if($category->subcategories()->count() > 0){
                return $this->error('Category has subcategories. Cannot delete.', 400);
            }
            $category->delete();
            return $this->success([], 'Category deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->error('Failed to delete category: ' . $e->getMessage(), 400);
        }
    }
}
