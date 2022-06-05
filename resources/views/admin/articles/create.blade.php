@component('admin.layouts.content',['title' => 'ایجاد مقاله جدید'])
    @slot('breadcrumb')

        <li class="breadcrumb-item"><a href="{{route('admin.admin')}}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="/admin/articles">لیست مقالات</a></li>
        <li class="breadcrumb-item active">ایجاد مقاله جدید</li>
    @endslot

    <!-- general form elements -->
    <div class="card card-primary col-12">
        <div class="card-header">
            <h3 class="card-title">ایجاد مقاله</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{route('admin.articles.store')}}"  id="theForm" method="POST" role="form" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                @include('admin.layouts.errors')

                <div class="form-group ">
                    <label for="title">عنوان</label>
                    <input type="text" class="form-control" value="{{old('title')}}" name="title" id="title" placeholder="عنوان را وارد کنید">
                </div>
                <div class="form-group ">
                    <label for="reading_time">مدت زمان لازم برای مطالعه ( برحسب دقیقه و به عدد )</label>
                    <input type="text" class="form-control" name="reading_time" id="reading_time" value="{{old('reading_time')}}" placeholder="مدت زمان به دقیقه وارد کنید ">
                </div>

                <div class="form-group ">
                    <label for="key_words">کلمات کلیدی</label>
                    <input type="text" class="form-control" name="key_words" value="{{old('key_words')}}" id="key_words" placeholder="کلمات کلیدی را وارد کنید">
                </div>
{{--                <div class="form-group ">--}}
{{--                    <label for="image">تصویر مقاله</label>--}}
{{--                    <input type="file" class="form-control" value="{{old('image')}}" name="image" id="image" >--}}
{{--                    --}}
{{--                </div>--}}

                <div class="input-group align-items-center mt-lg-5">
                    <label for="image">تصویر مقاله :</label>
                    <input type="text" id="image" class="form-control mr-3" name="image"
                           aria-label="Image" aria-describedby="button-image" value="{{old('image')}}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button-image">انتخاب</button>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="description">توضیحات</label>
                    <textarea class="form-control"   name="description" id="description" placeholder="توضیحات را وارد کنید . . .">{{old('description')}}</textarea>
                </div>

                <div class="form-group ">
                    <label for="editor">متن</label>
                    <textarea class="form-control" name="text" id="editor" placeholder="متن خود را وارد کنید . . .">{{old('text')}}</textarea>
                </div>



            </div>
            <!-- /.card-body -->
            <div id="article_preview" class="btn mb-4 btn-warning">پیش نمایش مقاله</div>
            <div class="card-footer">
                <div id="submit" class="btn btn-primary">ایجاد مقاله</div>
            </div>
        </form>
    </div>
    <!-- /.card -->

@section('script')
    <script>

        CKEDITOR.replace('editor', {filebrowserImageBrowseUrl: '/file-manager/ckeditor'});

        var form  = $('#theForm');

        $('#article_preview').on('click' , function () {

            form.attr('action' , "{{route('article.preview')}}") ;
            form.attr("target" , "_blank");
            form.submit();

        })

        $('#submit').on('click' , function () {

            form.attr('action' , "{{route('admin.articles.store')}}") ;
            form.removeAttr("target");
            form.submit();

        })


        document.addEventListener("DOMContentLoaded", function() {

            document.getElementById('button-image').addEventListener('click', (event) => {
                event.preventDefault();

                window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
            });
        });

        function fmSetLink($url) {
            document.getElementById('image').value = $url;
        }

    </script>
@endsection


@endcomponent
