<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductRate;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Array_;
use function Sodium\increment;

class ProductController extends Controller
{
    public function index($id)
    {

        $category = Category::where('id' , $id)->first();

        $children = $category->child()->pluck('id');
        $children->add($category->id);

        $products = collect();

        foreach ($children as $cat)
        {
            $productsOfChild = Category::where('id' , $cat)->first()->products;
            $products = $products->concat($productsOfChild);
        }


        foreach ($products as $product){

            $product['attr'] = collect();
            foreach ($product->attributes as $attr) {
                $product['attr']->put($attr->name , $attr->pivot->value->value) ;
            }
        }

        return view('product.products', compact('products' , 'category'));
    }
    public function single($slug)
    {

        $product = Product::where('slug' , $slug)->first();
        $product->increment('observed');
        $category = new Category();
        $similarProducts = $category->find($product->categories->pluck('id')[0])->products;

        return view('product.single' , compact('product' , 'similarProducts'));
    }

    public function productlist($id)
    {
        $category = Category::where('id' , $id)->first();
//        $products =  $category->products;



        $children = $category->child()->pluck('id');
        $children->add($category->id);

        $products = collect();

        foreach ($children as $cat)
        {
            $productsOfChild = Category::where('id' , $cat)->first()->products;
            $products = $products->concat($productsOfChild);
        }






        foreach ($products as $product){
            $product['pricee'] = $product->price != null ? $product->price->off_price : 0 ;
            $product['inventory'] = $product->inventory() != null ? $product->inventory() : 0 ;
            $product['avrage'] =  $product->rate->avg('rate');
            $product['leng'] = count($product->rate);
            $product['isChecked'] = false;
//            $product['attr'] = collect();
            $product['attr'] = (attributeAnalysis($product->id));
//            foreach ($product->attributes as $attr) {
//                if(strlen($attr->pivot->value->value) < 46){
//                    $product['attr']->put($attr->name , $attr->pivot->value->value) ;
//                }
//
//            }
        }
        return $products;
    }

    public function submitRate(Request $request)
    {
        $user_ip = $request->getClientIp();


        if (is_null(ProductRate::where([['user_ip', $user_ip], ['product_id', $request->product_id]])->first())) {

            ProductRate::insert([
                'user_ip' => $user_ip,
                'rate' => $request->rate,
                'product_id' => $request->product_id,
            ]);


        }
        if (! is_null(ProductRate::where([['user_ip', $user_ip], ['product_id', $request->product_id]])->first())) {

            $productRate = ProductRate::where([['user_ip', $user_ip], ['product_id', $request->product_id]])->first();
            $productRate->update([
                'rate' => $request->rate,
            ]);


        }

    }

}
