@component('profile.layouts.content')

    @slot('profileTitle')
        <span>ویرایش آدرس ها</span>
    @endslot

    <div class="addresses">

        <div class="addresses_head">
            <h3>  آدرس های شما : </h3>
            <span> ( بر روی آدرس پیش فرض کلیک کنید )</span>
        </div>

        @if(count($addresses) > 0)
            @foreach($addresses as $address)
                <div class="address_item_parent">
                    <div class="address_item {{$address->default_address == 1 ? 'select_address' : ''}} ">
                        <div class="selected_address"></div>
                        <input type="hidden" value="{{$address->id}}">
                        <span class="translate">استان :  <span>   {{ $address->province->name }}  &nbsp; </span> شهر : <span> {{$address->city->name}} &nbsp; </span>  نشانی : <span>  {{$address->description}} &nbsp; </span> کد پستی : <span> {{$address->postal_code}}&nbsp; </span> شماره تماس : <span>  {{$address->home_number}}  </span></span>
                    </div>
                    <div class="delete_address_btn">
                        <a href="{{route('profile.deleteAddress' , $id = $address->id)}}">حذف</a>
                    </div>
                </div>
            @endforeach
        @endif


        <div class="add_address">
            <h2>افزودن آدرس جدید :</h2>
            <form id="addAddress" action="{{route('profile.addAddress')}}" method="post">
                @csrf
                <div class="add_address_item">
                    <label class="add_address_item_title">استان : </label>
                    <select class="js-example-basic-single" style="width: 200px; height: 50px" id="province" name="province_id" >
                        @foreach(\App\Province::all() as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="city_section" class="add_address_item">
                    <label class="add_address_item_title">شهر : </label>
                    <select  id="cities" style="width: 200px; height: 50px" class="js-example-basic-single" name="city_id" >

                    </select>
                </div>
                <div class="add_address_item">
                    <label class="add_address_item_title">آدرس پستی : </label>
                    <textarea name="description"  cols="40" rows="3"></textarea>
                </div>
                <div  class="add_address_item">
                    <label class="add_address_item_title">کد پستی : </label>
                    <input  name="postal_code" type="number">
                </div>
                <div  class="add_address_item">
                    <label class="add_address_item_title">شماره تماس : </label>
                    <input  name="home_number" type="number">
                </div>
            </form>
            <div onclick="document.getElementById('addAddress').submit()" class="btnn add_address_btn">افزودن آدرس جدید</div>

        </div>


    </div>


@section('script')
    <script>

        var cities = [];
        axios.get('/profile/getcities')
            .then(response =>{ cities = response.data })
            .catch(function (error) {
                console.log(error);
            })


        $('#city_section').fadeOut(0);
        $('#province').on('select2:select', function (e) {
            var province = this.value;

            document.getElementById("cities").innerHTML = '';

            cities.forEach(myFunction);

            function myFunction(item, index) {
                if(item['province_id'] == province){
                    document.getElementById("cities").innerHTML +=   '<option value='+item['id']+'>'+item['name']+'</option>'
                }
            }

            $('#city_section').fadeIn(1000);

        });


        $('.address_item').click(function () {

            $data = { id : $(this).children('input')[0].value};
            axios.post('/profile/setDefaultAddress' , $data )
                .then(response =>{
                    $('.address_item').css({'border' : '2px solid rgba(0,0,0,0.1)'});
                    $(this).css({'border' : '2px solid rgba(7, 51, 227, 0.6)'});
                    window.FlashMessage.success('آدرس پیش فرض شما با موفقیت تغییر یافت !' , {
                        progress: true,
                        timeout: 3000,

                    });
                })
                .catch(function (error) {
                    console.log(error);
                })

        })

    </script>
@endsection

@endcomponent


