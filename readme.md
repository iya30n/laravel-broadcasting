<h2 dir="rtl">معرفی</h2>
<p dir="rtl">از وقتی که با <a href="https://laravel.com/docs/5.6/broadcasting">broadcasting</a> لاراول آشنا شدم آموزش فارسی و کاملی برای آن پیدا نکردم همچنین در گروه های مختلف شاهد این بودم که عده ی زیادی نیاز به یادگیری و درک این مبحث دارند این امر باعث شد که به فکر نوشتن آموزش ساده و قابل فهم برای همه بی افتم.
برای کسانی که با این قابلیت آشنایی ندارند بگویم که broadcasting برای ارسال نوتیفیکیشن به کاربر های آنلاین هم به صورت خصوصی هم به صورت عمومی بدون نیاز به refresh شدن صفحه و همچنین ساخت سیستم چت realtime به کار می رود البته اینها تنها کاربرد های این سرویس نیست و بسته به ایده های شما می تواند کاربرد های مختلف و بهتری هم داشته باشد.
برای مثال پروژه ی خیلی ساده هم در اختیار شما قرار میدم تا با خوندن کد ها درک بهتری داشته باشید.</p>

<h2 dir="rtl">شروع کار</h2>
<p dir="rtl">برای کار با این سرویس نیازمند استفاده از درایور ها هستیم که باعث میشوند پروژه اصلی کمتر درگیر شده و فشار کمتری هم به سرور بیاید.
من در این آموزش از درایور pusher استفاده می کنم چون در هاست های اشتراکی قابل استفاده است و همچنین نیاز به کانفیگ کردن خاصی ندارد همچنین شما میتونید از redis و socket.io هم استفاده کنید که این امر نیازمند سرور اختصاصی هست.</p>

<p dir="rtl">
    اولین کاری که می کنیم این است که اول باید BroadcastServiceProvider رو در مسیر confog/app.php از کامنت خارج کنیم.
اگر مثل من از لاراول 5.6 استفاده می کنید بهتر است از نسخه ی pusher 3.0 استفاده کنید در غیر این صورت نسخه ی سازگار با لاراول خودتون رو نصب کنید.
</p>

```
composer require pusher/pusher-php-server "~3.0"
```

<p dir="rtl">
    بعد از نصب شدن درایور به سایت <a href="https://pusher.com/">pusher</a> بروید و ثبت نام کنید.
</p>

<p dir="rtl">
    پس از ثبت نام اپلیکیشن خود را رجیستر کنید تا app_id , app_key , app_secret , app_cluster برای شما ساخته شود.
این اطلاعات را از سایت pusher به فایل env پروژه در قسمت های مشخص شده وارد کنید و BROADCAST_DRIVER را به pusher تغییر دهید.
</p>

<p dir="rtl">
    حال شما باید پکیج منیجر جاوااسکریپت یعنی npm را با دستور npm install نصب کنید.
</p>


<p dir="rtl">
   قدم بعدی نصب کتابخانه های pusher.js و laravel echo با دستور زیر است.
</p>

```
npm install --save laravel-echo pusher-js
```
<p dir="rtl">
    پس از نصب شدن کتابخانه های pusher و laravel echo فایل bootstrap.js در مسیر resources/assets/js را باز کرده و کد های مربوط به laravel echo را از کامنت خارج کنید. همچنین میتوانید در همان مسیر کد های مربوط به vue js را در فایل app.js پاک کنید(در صورتی که در پروژه از این فریمورک استفاده نمی کنید).
</p


<p dir="rtl">
    حال پروژه آماده ی کار با broadcasting است.
</p>
