@component('admin.layouts.content',['title' => 'لیست محصولات'])
    @slot('breadcrumb')

        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item active">محصولات</li>
    @endslot


    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">لیست محصولات</h3>

                <div class="card-tools d-flex">
                    <form action="">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="جستجو">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="btn btn-info mr-3">
                        @can('product_create')
                            <a style="color: #fff" href="{{route('admin.products.create')}}">ایجاد محصول جدید</a>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>آی دی</th>
                        <th>نام محصول</th>
                        <th>گروه</th>
                        <th>موجودی</th>
                        <th>قیمت</th>
                        <th>قیمت تخفیفی</th>
                        <th>اقدامات</th>
                    </tr>
                    @foreach($products as $product)
                        <tr>
                            <td>{{$product->id}}</td>
                            <td><a target="_blank"
                                   href="{{route('product.single' , $product->slug)}}">{{$product->name}}</a></td>
                            <td>{{$product->categories[0]->name}}</td>
                            <td>{{$product->inventory()}}</td>
                            <td>{{$product->price->price ?? 0}}</td>
                            <td>{{$product->price->off_price ?? 0}}</td>

                            <td class="d-flex">
                                @can('product_edit')
                                    <a class="btn btn-sm btn-primary "
                                       href="{{route('admin.products.edit' , $product)}}">ویرایش</a>
                                @endcan
                                @can('product_gallery')
                                    <a href="{{ route('admin.products.gallery.index' ,  $product->id) }}"
                                       class="btn btn-sm btn-warning mr-2">گالری تصاویر</a>
                                @endcan
                                @can('price_inventory')
                                    <a href="{{ route('admin.price.singlePrice.edit' ,  $product ) }}"
                                       class="btn btn-sm btn-info mr-2">ویرایش قیمت و موجودی</a>
                                @endcan
                                @can('product_delete')
                                    <form method="POST" action="{{route('admin.products.destroy' ,  $product)}}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger mr-2" type="submit">حذف</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">{{$products->render()}}</div>
        </div>
        <!-- /.card -->
    </div>


@endcomponent
