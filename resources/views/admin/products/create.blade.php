@component('admin.layouts.content',['title' => 'ایجاد محصول جدید'])
    @slot('breadcrumb')

        <li class="breadcrumb-item"><a href="{{route('admin.admin')}}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.products.index')}}">لیست محصولات</a></li>
        <li class="breadcrumb-item active">ایجاد محصول جدید</li>
    @endslot


    <!-- general form elements -->
    <div class="card card-primary col-12">
        <div class="card-header">
            <h3 class="card-title">ایجاد محصول</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <div id="attributes" data-attributes="{{ json_encode(\App\Attribute::all()->pluck('name')) }}"></div>
        <form action="{{route('admin.products.store')}}" method="POST" role="form" enctype="multipart/form-data">

            @csrf
            <div class="card-body">
                @include('admin.layouts.errors')
                <div class="form-group ">
                    <label for="name">عنوان</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="نام محصول را وارد کنید">
                </div>
                <div class="form-group ">
                    <label for="description">توضیحات</label>
                    <textarea class="form-control"  name="description" id="description" placeholder="توضیحات را وارد کنید . . ."></textarea>
                </div>
{{--                <div class="form-group ">--}}
{{--                    <label for="inventory">موجودی</label>--}}
{{--                    <input type="number" class="form-control" name="inventory" id="inventory" placeholder="موجودی">--}}
{{--                </div>--}}

{{--                <div class="form-group ">--}}
{{--                    <label for="price">قیمت</label>--}}
{{--                    <input type="number" class="form-control" name="price" id="price" placeholder="قیمت">--}}
{{--                </div>--}}
{{--                <div class="form-group ">--}}
{{--                    <label for="off_price"> قیمت تخفیف خورده</label>--}}
{{--                    <input type="number" class="form-control" name="off_price" id="off_price" placeholder=" قیمت تخفیف خورده">--}}
{{--                </div>--}}
{{--                <div class="form-group ">--}}
{{--                    <label for="amazon_price">قیمت آمازون</label>--}}
{{--                    <input type="number" class="form-control" name="amazon_price" id="amazon_price" placeholder="قیمت آمازون">--}}
{{--                </div>--}}
{{--                <div class="form-group ">--}}
{{--                    <label for="amazon_link">لینک آمازون</label>--}}
{{--                    <input type="text" class="form-control" name="amazon_link" id="amazon_link" placeholder="لینک آمازون">--}}
{{--                </div>--}}


                <div class="input-group align-items-center mt-lg-5">
                    <label for="image_label">تصویر محصول : </label>
                    <input type="text" id="image_label" class="form-control mr-3" name="image"
                           aria-label="Image" aria-describedby="button-image">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button-image">انتخاب</button>
                    </div>
                </div>


                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label mt-4">دسته بندی ها</label>
                    <select class="form-control" name="categories[]" id="categories" multiple>
                        @foreach(\App\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <h6>ویژگی محصول</h6>
                <hr>
                <div id="attribute_section">

                </div>
                <button class="btn btn-sm btn-danger" type="button" id="add_product_attribute">ویژگی جدید</button>


                <div class="form-group mt-4">
                    <label for="editor">بررسی تخصصی</label>
                    <textarea class="form-control" name="pro_details" id="editor" placeholder="متن خود را وارد کنید . . .">{{old('pro_details')}}</textarea>
                </div>

            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" id="submit" class="btn btn-primary">ایجاد محصول</button>
            </div>
        </form>
    </div>
    <!-- /.card -->


    @slot('script')
        <script>

            CKEDITOR.replace('editor', {filebrowserImageBrowseUrl: '/file-manager/ckeditor'});


            document.addEventListener("DOMContentLoaded", function() {

                document.getElementById('button-image').addEventListener('click', (event) => {
                    event.preventDefault();

                    window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
                });
            });

            // set file link
            function fmSetLink($url) {
                document.getElementById('image_label').value = $url;
            }

            $('#categories').select2({
                'placeholder' : 'دسترسی مورد نظر را انتخاب کنید'
            })


            let changeAttributeValues = (event , id) => {
                let valueBox = $(`select[name='attributes[${id}][value]']`);


                //
                $.ajaxSetup({
                    headers : {
                        'X-CSRF-TOKEN' : document.head.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type' : 'application/json'
                    }
                })
                //
                $.ajax({
                    type : 'POST',
                    url : '/admin/attribute/values',
                    data : JSON.stringify({
                        name : event.target.value
                    }),
                    success : function(res) {
                        valueBox.html(`
                            <option value="" selected>انتخاب کنید</option>
                            ${
                            res.data.map(function (item) {
                                return `<option value="${item}">${item}</option>`
                            })
                        }
                        `);
                    }
                });
            }

            let createNewAttr = ({ attributes , id }) => {

                return `
                    <div class="row" id="attribute-${id}">
                        <div class="col-5">
                            <div class="form-group">
                                 <label>عنوان ویژگی</label>
                                 <select name="attributes[${id}][name]" onchange="changeAttributeValues(event, ${id});" class="attribute-select form-control">
                                    <option value="">انتخاب کنید</option>
                                    ${
                    attributes.map(function(item) {
                        return `<option value="${item}">${item}</option>`
                    })
                }
                                 </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                 <label>مقدار ویژگی</label>
                                 <select name="attributes[${id}][value]" class="attribute-select form-control">
                                        <option value="">انتخاب کنید</option>
                                 </select>
                            </div>
                        </div>
                         <div class="col-2">
                            <label >اقدامات</label>
                            <div>
                                <button type="button" class="btn btn-sm btn-warning" onclick="document.getElementById('attribute-${id}').remove()">حذف</button>
                            </div>
                        </div>
                    </div>
                `
            }

            $('#add_product_attribute').click(function() {
                let attributesSection = $('#attribute_section');
                let id = attributesSection.children().length;

                let attributes = $('#attributes').data('attributes');

                attributesSection.append(
                    createNewAttr({
                        attributes,
                        id
                    })
                );

                $('.attribute-select').select2({ tags : true });
            });
        </script>
    @endslot
@endcomponent


