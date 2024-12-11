<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

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
            $query->with('subcategories');

            // Pagination
            $categories = $query->paginate($request->get('per_page', config('constants.per_page'))); // default to 15 items per page

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully.',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to retrieve categories: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $category = new Category();
            $category->name = $request->name;
            $category->parent_id = $request->parent_id;
            $category->save();

            return response()->json(['success' => true, 'message' => 'Category created successfully.', 'data' => $category], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to create category: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::with('subcategories')->findOrFail($id);
            return response()->json(['success' => true, 'message' => 'Category retrieved successfully.', 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to retrieve category: ' . $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->parent_id = $request->parent_id;
            $category->save();

            return response()->json(['success' => true, 'message' => 'Category updated successfully.', 'data' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to update category: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to delete category: ' . $e->getMessage()], 500);
        }
    }
}