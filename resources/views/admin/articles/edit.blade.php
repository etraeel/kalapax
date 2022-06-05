@component('admin.layouts.content',['title' => 'ویرایش مقاله جدید'])
    @slot('breadcrumb')

        <li class="breadcrumb-item"><a href="{{route('admin.admin')}}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="/admin/articles">لیست مقالات</a></li>
        <li class="breadcrumb-item active">ویرایش مقاله جدید</li>
    @endslot

    <!-- general form elements -->
    <div class="card card-primary col-12">
        <div class="card-header">
            <h3 class="card-title">ویرایش مقاله</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{route('admin.articles.update' , $article)}}" method="POST" role="form">
            @csrf
            @method('PATCH')
            <div class="card-body">
                @include('admin.layouts.errors')
                <div class="form-group ">
                    <label for="title">عنوان</label>
                    <input type="text" class="form-control" name="title" id="title" value="{{$article->title}}">
                </div>

                <div class="form-group ">
                    <label for="writer">نویسنده :</label>
                    <input type="text" class="form-control" name="writer" id="writer" value="{{$article->writer}}">
                </div>
                <div class="form-group ">
                    <label for="reading_time">مدت زمان لازم برای مطالعه ( برحسب دقیقه و به عدد )</label>
                    <input type="text" class="form-control" name="reading_time" id="reading_time" value="{{$article->reading_time}}">
                </div>

                <div class="form-group ">
                    <label for="key_words">کلمات کلیدی</label>
                    <input type="text" class="form-control" name="key_words" id="key_words" value="{{$article->key_words}}">
                </div>

                <div class="input-group align-items-center mt-lg-5">
                    <label for="image">تصویر مقاله :</label>
                    <input type="text" id="image" class="form-control mr-3" name="image"
                           aria-label="Image" aria-describedby="button-image" value="{{$article->image}}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button-image">انتخاب</button>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="description">توضیحات</label>
                    <textarea class="form-control"  name="description" id="description" >{{$article->description}}</textarea>
                </div>

                <div class="form-group ">
                    <label for="editor">متن</label>
                    <textarea lang="en"  class="form-control"  name="text" id="editor" >{{$article->text}}</textarea>
                </div>



            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">ویرایش مقاله</button>
            </div>
        </form>
    </div>
    <!-- /.card -->

@section('script')
    <script>
        CKEDITOR.replace('editor', {filebrowserImageBrowseUrl: '/file-manager/ckeditor'});

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
