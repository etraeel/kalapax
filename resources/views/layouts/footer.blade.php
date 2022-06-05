
<div class="home_footer">
    <div class="home_footer_1">
        <div>
            <i class="fad fa-gift"></i>
            <span style="color: #e8505b">همیشه تخفیف</span>
        </div>
        <div>
            <i class="fal fa-barcode-read"></i>
            <span>ضمانت اصل بودن</span>
        </div>
        <div>
            <i class="fad fa-handshake"></i>
            <span>تحویل در محل</span>
        </div>
        <div>
            <i class="fal fa-shipping-fast"></i>
            <span>تحویل فوری</span>
        </div>
        <div>
            <i class="fad fa-user-headset"></i>
            <span>پشتیبانی ۲۴ ساعته</span>
        </div>
    </div>
    <div class="home_footer_2">
        <span>هفت روز هفته ، <span style="color: #e8505b"> ۲۴ ساعت </span> شبانه‌روز پاسخگوی شما هستیم</span>
        <span style="margin-top: 20px;">شماره تماس : {!!  \App\Setting::where('name' , 'site_phone_number')->first()->value !!}</span>
        <span>آدرس ایمیل : {{\App\Setting::where('name' , 'site_email')->first()->value}}</span>
    </div>
    <div class="home_footer_3">
        <span> ما را در شبکه‌های اجتماعی دنبال کنید:</span>
        <div>
            <a href="{{\App\Setting::where('name' , 'site_linkedin')->first()->value}}"> <i class="fab fa-linkedin"></i></a>
            <a href="{{\App\Setting::where('name' , 'site_twitter')->first()->value}}"><i class="fab fa-twitter"></i></a>
            <a href="{{\App\Setting::where('name' , 'site_telegram')->first()->value}}"><i class="fab fa-telegram-plane"></i></a>
            <a href="{{\App\Setting::where('name' , 'site_instagram')->first()->value}}"><i class="fab fa-instagram"></i></a>

        </div>
    </div>
    <div class="home_footer_5">
        <span>استفاده از مطالب فروشگاه فقط برای مقاصد غیرتجاری و با ذکر منبع بلامانع است. کلیه حقوق این سایت متعلق به شرکت وب پکس می‌باشد. </span>

    </div>
</div>
