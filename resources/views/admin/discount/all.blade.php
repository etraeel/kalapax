@component('admin.layouts.content' , ['title' => 'لیست تخفیف'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="{{route('admin.admin')}}">پنل مدیریت</a></li>
        <li class="breadcrumb-item active">لیست تخفیف‌ها</li>
    @endslot

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تخفیف‌ها</h3>

                    <div class="card-tools d-flex">
                        <form action="">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="جستجو"
                                       value="{{ request('search') }}">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                        <div class="btn-group-sm mr-1">
                            @can('discount_create')
                                <a href="{{ route('admin.discount.create') }}" class="btn btn-info">ایجاد تخفیف جدید</a>
                            @endcan
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-striped" style="font-size: .9rem">
                        <tbody>
                        <tr>
                            <th>آی‌دی</th>
                            <th>کد تخفیف</th>
                            <th>در صد تخفیف</th>
                            <th>مربوط به کاربر</th>
                            <th>مربوط به محصول</th>
                            <th>مربوط به دسته</th>
                            <th>تخفیف تا سقف (تومان)</th>
                            <th>تعداد کد تخفیف</th>
                            <th>مهلت استفاده</th>
                            <th>اقدامات</th>
                        </tr>

                        @foreach($discounts as $discount)
                            <tr>
                                <td>{{ $discount->id }}</td>
                                <td>{{ $discount->code }}</td>
                                <td>{{ $discount->percent }}</td>
                                <td>{{ $discount->users->count() ? $discount->users->pluck('name')->join(', ') : 'همه کاربران' }}</td>
                                <td>{{ $discount->products->count() ? $discount->products->pluck('title')->join(', ') : 'همه محصولات' }}</td>
                                <td>{{ $discount->categories->count() ?  $discount->categories->pluck('name')->join(', ') : 'همه دسته‌ها' }}</td>
                                <td class="m-auto">{{ $discount->max_discount_price != 0 ? $discount->max_discount_price : 'بدون محدودیت' }}</td>
                                <td>{{ $discount->number_discount_fixed != -1 ? $discount->number_discount." از " .$discount->number_discount_fixed  : 'بدون محدودیت' }}</td>
                                <td>{{ jdate($discount->expired_at)->ago() }}</td>
                                <td class="d-flex">
                                    {{--                                    // permissions--}}
                                    @can('discount_edit_delete')
                                        <form action="{{ route('admin.discount.destroy' , $discount->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger ml-1">حذف</button>
                                        </form>
                                        <a href="{{ route('admin.discount.edit' , $discount->id) }}"
                                           class="btn btn-sm btn-primary ml-1">ویرایش</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $discounts->appends([ 'search' => request('search') ])->render() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

@endcomponent
