<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('id');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if ($id) {
            $product = Product::with('categories', 'galleries')->find($id);

            if ($product) {
                return ResponseFormatter::success(
                    $product,
                    'Data Product Sukses Diambil'
                );
            } else {
                return ResponseFormatter::error(
                    $product,
                    'Data Product Tidak Ada',
                    404
                );
            }
        }

        $product = Product::with('categories', 'galleries');
        if ($name) {
            $product->where('name', 'like', '%' . $name . '%');
        }

        if ($description) {
            $product->where('description', 'like', '%', $description . '%');
        }

        if ($tags) {
            $product->where('tag$tags', 'like', '%', $tags . '%');
        }

        if ($price_from) {
            $product->where('price', '>=', $price_from);
        }

        if ($price_to) {
            $product->where('price', '<=', $price_to);
        }

        if ($categories) {
            $product->where('categories',  $categories);
        }
        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data Product Sukses Diambil'
        );
    }
}