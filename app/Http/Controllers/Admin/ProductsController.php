<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use App\Attribute;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:products_show')->only(['index']);
        $this->middleware('can:product_create')->only(['create' , 'store']);
        $this->middleware('can:product_edit')->only(['edit' , 'update']);
        $this->middleware('can:product_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::query();

        if ($keyword = request('search')) {
            $products->where('name', 'LIKE', "%{$keyword}%")->orWhere('id', 'LIKE', "%{$keyword}%");
        }

        $products = $products->latest()->paginate(15);
        return view('admin.products.all', compact('products'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate( [

            'name' => ['required', 'max:255'],
            'description' => ['required', 'string'],
//            'price' => ['integer'],
//            'off_price' => ['integer'],
//            'amazon_price' => ['integer'],
//            'amazon_link' => ['string'],
//            'inventory' => ['integer'],
            'image' => ['required'],
            'categories' => 'required',
            'attributes' => 'array',
            'attributes.*.name' => ['required' , 'max:255'],
            'attributes.*.value' => ['required' , 'max:255'],
            'pro_details' => [ 'nullable','string'],
        ]);


        $product = Product::create($data);

        $product->categories()->sync($data['categories']);

        if(isset($data['attributes']))
            $this->attachAttributesToProduct($product, $data);

        if ($request->has('off_price')){
            $product->forceFill([
                'off_price' => $request['off_price']
            ])->save();
        }
        if ($request->has('amazon_price')){
            $product->forceFill([
                'amazon_price' => $request['amazon_price']
            ])->save();
        }
        if ($request->has('amazon_link')){
            $product->forceFill([
                'amazon_link' => $request['amazon_link']
            ])->save();
        }


        alert()->success('محصول با موفقیت ایجاد شد ');
        return redirect(route('admin.products.index'));

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {


//        dd($request->all());
        $data = $request->validate( [

            'name' => ['required', 'max:255'],
            'description' => ['required', 'string'],
//            'price' => ['integer'],
//            'off_price' => ['integer'],
//            'amazon_price' => ['integer'],
//            'amazon_link' => ['string'],
//            'inventory' => ['integer'],
            'image' => ['required'],
            'categories' => 'required',
            'attributes.*.name' => ['required' , 'max:255'],
            'attributes.*.value' => ['required' , 'max:255'],
            'pro_details' => [ 'nullable','string'],

        ]);


        $product ->update($data);

        $product->categories()->sync($data['categories']);

        $product->attributes()->detach();
        if(isset($data['attributes']))
        $this->attachAttributesToProduct($product, $data);

        if ($request->has('off_price')){
            $product->forceFill([
                'off_price' => $request['off_price']
            ])->save();
        }
        if ($request->has('amazon_price')){
            $product->forceFill([
                'amazon_price' => $request['amazon_price']
            ])->save();
        }
        if ($request->has('amazon_link')){
            $product->forceFill([
                'amazon_link' => $request['amazon_link']
            ])->save();
        }


        alert()->success('محصول با موفقیت ویرایش شد ');
        return redirect(route('admin.products.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        $product->comments()->delete();
        alert()->success('محصول با موفقیت حذف شد ');
        return back();
    }

    /**
     * @param Product $product
     * @param array $validData
     */
    protected function attachAttributesToProduct(Product $product, array $data): void
    {
        $attributes = collect($data['attributes']);
        $attributes->each(function ($item) use ($product) {
            if (is_null($item['name']) || is_null($item['value'])) return;

            $attr = Attribute::firstOrCreate(
                ['name' => $item['name']]
            );

            $attr_value = $attr->values()->firstOrCreate(
                ['value' => $item['value']]
            );

            $product->attributes()->attach($attr->id, ['value_id' => $attr_value->id]);
        });
    }
}
