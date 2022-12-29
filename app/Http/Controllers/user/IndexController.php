<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    public function indexCategory()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'Categories' => $categories,
                'message' => 'data has been retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'there is been an error',
                'error message' => $e->getMessage(),
            ], 500);
        }
    }

    public function indexExperts($id)
    {
        try {
            $category = Category::find($id);
            $experts = $category->experts;

            return response()->json([
                'message' => 'data has been retieved successfully',
                'experts' => $experts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'there is been an error',
                'error message' => $e->getMessage(),
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $rules = [
            'name' => 'string|min:3|required',
        ];
        $validator = Validator::make(
            $request->only('name'),
            $rules
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $expert = Expert::where('name', $request->name)->with('category')->get();
        return response()->json(['expert' => $expert], 200);
    }

    public function expertDetails($id)
    {
        $expert = Expert::where('id', $id)->with('category')->with('opened_time')->get();
        return response()->json(['expert' => $expert], 200);
    }
}
