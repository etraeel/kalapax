@component('admin.layouts.content' , ['title' => 'لیست تصاویر'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="{{route('admin.admin')}}">پنل مدیریت</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.products.index')}}"> {{ $product->name }}</a></li>
        <li class="breadcrumb-item active">گالری تصاویر</li>
    @endslot

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تصاویر</h3>

                    <div class="card-tools d-flex">
                        <div class="btn-group-sm mr-1">
                            <a href="{{ route('admin.products.gallery.create' ,  $product) }}" class="btn btn-info">ثبت تصویر جدید</a>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        @foreach($images as $image)
                            <div class="col-sm-2  justify-content-center align-items-center border border-light p-4">
                                <a target="tab"  class="d-flex w-75 h-75" href="{{ url($image->image) }}">
                                    <img  src="{{ url($image->image) }}" class="img-fluid mb-2 rounded" alt="{{ $image->alt }}">
                                </a>
                                <form action="{{ route('admin.products.gallery.destroy' , ['product' => $product->id , 'gallery' => $image->id]) }}" id="image-{{ $image->id }}" method="post">
                                    @method('delete')
                                    @csrf
                                </form>
                                <a  href="{{ route('admin.products.gallery.edit' , ['product' => $product->id , 'gallery' => $image->id]) }}" class="btn btn-sm btn-primary mr-4">ویرایش</a>
                                <a  href="#" class="btn btn-sm btn-danger" onclick="document.getElementById('image-{{ $image->id }}').submit()">حذف</a>

                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $images->render() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

@endcomponent
