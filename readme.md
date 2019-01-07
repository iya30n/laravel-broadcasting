<h2 dir="rtl">معرفی</h2>
<p dir="rtl">از وقتی که با <a href="https://laravel.com/docs/5.6/broadcasting">broadcasting</a> لاراول آشنا شدم آموزش فارسی و کاملی برای آن پیدا نکردم همچنین در گروه های مختلف شاهد این بودم که عده ی زیادی نیاز به یادگیری و درک این مبحث دارند این امر باعث شد که به فکر نوشتن آموزش ساده و قابل فهم برای همه بی افتم.
برای کسانی که با این قابلیت آشنایی ندارند بگویم که broadcasting برای ارسال نوتیفیکیشن به کاربر های آنلاین هم به صورت خصوصی هم به صورت عمومی بدون نیاز به refresh شدن صفحه و همچنین ساخت سیستم چت realtime به کار می رود البته اینها تنها کاربرد های این سرویس نیست و بسته به ایده های شما می تواند کاربرد های مختلف و بهتری هم داشته باشد.
برای مثال پروژه ی خیلی ساده هم در اختیار شما قرار میدهم تا با خواندن کد ها درک بهتری داشته باشید.</p>

<h2 dir="rtl">شروع کار</h2>
<p dir="rtl">برای کار با این سرویس نیازمند استفاده از درایور ها هستیم که باعث میشوند پروژه اصلی کمتر درگیر شده و فشار کمتری هم به سرور بیاید.
من در این آموزش از درایور pusher استفاده می کنم چون در هاست های اشتراکی قابل استفاده است و همچنین نیاز به کانفیگ کردن خاصی ندارد همچنین شما میتونید از redis و socket.io هم استفاده کنید که این امر نیازمند سرور اختصاصی هست.</p>

<br><br>

<h3 dir="rtl">آماده سازی درایور pusher</h3>

<p dir="rtl">
    اولین کاری که می کنیم این است که اول باید BroadcastServiceProvider را در مسیر confog/app.php از کامنت خارج کنیم.
اگر مثل من از لاراول 5.6 استفاده می کنید بهتر است از نسخه ی pusher 3.0 استفاده کنید در غیر این صورت نسخه ی سازگار با لاراول خود را نصب کنید.
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
</p>


<p dir="rtl">
    مدل  , migration , controller برای چت طبق نیازتان ایجاد کنید.
</p>


<p dir="rtl">
    حال پروژه آماده ی کار با broadcasting است.
</p>

<br><br>

<h3 dir="rtl">آماده سازی کلاس های broadcasting</h3>

<p dir="rtl">
    برای مثال ما قصد داریم همین سیستم چت را در نظر بگیریم. بدین منظور یک event با نام ChatEvent می سازیم.
</p>

```
php artisan make:event ChatEvent
```

<p dir="rtl">
    حال در مسیر app/Events میتوان event ساخته شده را مشاهده کرد.
</p>


<p dir="rtl">
    حالا وارد event ساخته شده شوید و از اینترفیس ShouldBroadcast برای کلاس ChatEvent به صورت زیر استفاده کنید.
</p>

```
class ChatEvent implements ShouldBroadcast
{...}
```

<p dir="rtl">
    حال باید پراپرتی های کلاس ChatEvent را مشخص کنید هر چیز که میخواهید broadcast شود را به صورت پراپرتی public تعریف کنید اگر میخواهید از braodcasting به صورت private استفاده کنید می بایست یک پراپرتی id هم تعریف کنید.
</p>

```
class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public $id;
```

<p dir="rtl">
    در constructor هم باید مقادیر را دریافت کنید و به پراپرتی ها مانند زیر پاس دهید. همچنین برای استفاده از public broadcasting نیازی به تعریف کردن id در constructor ندارید.
</p>

```
public function __construct($message , $id)
    {
        $this->message=$message;
        $this->id=$id;
    }
```

<p dir="rtl">
    اگر از این سرویس به صورت public استفاده می کنید می توانید تنها یک نام برای channel broadcast خود در تابع broadcastOn انتخاب کرده و به قسمت آماده سازی front end بروید. در غیر این صورت باید Channel را به PrivateChannel تغییر دهید و یک نام به همراه id کاربر تعریف کنید.
</p>

```
public function broadcastOn()
    {
        return new PrivateChannel('chat.'.$this->id);
        
        // return new Channel('chat'); //برای پابلیک
    }
```


<p dir="rtl">
   حال نوبت به channels.php می رسد که در پوشه ی routes می توان آن را مشاهده کرد.  در این فایل باید نام channel چت و شرط دریافت پیام را تعیین کرد. 
<p>
    
<p dir="rtl">
    زمانی که ما ChatEvent را فراخوانی می کنیم عملیات broadcast انجام می شود و هنگام فراخوانی نیازمند این هستیم که id آن کاربر که میخواهیم پیغام را ببیند را به ChatEvent به همراه message پاس دهیم و در نتیجه توسط آن، id کاربر آنلاین با این id مقایسه می شود و در صورت تطابق پیام را دریافت می کند. توجه داشته باشید که نام channel تشکیل شده از chat و id کاربر است تا هر کاربر چنل مختص خود را داشته باشد.
<p>
    
<p dir="rtl">
    اگر از لاراول 5.6 به بعد استفاده می کنید می توانید با دستور php artisan make:channel شرط درون تابع را به کلاس ساخته شده منتقل کنید و به جای تابع زیر از آن کلاس استفاده کنید.
</p>
    
```
Broadcast::channel("chat.{id}", function ($user, $id) {
    return $user->id == $id;
});
```

<br><br>

<h3 dir="rtl">آماده سازی front end</h3>

<p dir="rtl">
    از آنجا که وارد کردن کتابخانه های pusher , laravel echo به صورت دستی باعث شلوغ شدن کد ها و مدیریت دشوار تر کد ها می شود از webpack برای وارد کردن کتابخانه های نصب شده استفاده می کنیم.
</p>

<p dir="rtl">
    همچنین برای ارسال پیام از axios استفاده می کنیم.
</p>

<p dir="rtl">
    دستور npm run dev را درون ترمینال وارد کنید تا فایل app.js در پوشه ی public ایجاد شود. این فایل حاوی کتابخانه هایی از جمله pusher , laravel echo , axios , jquery و غیره می باشد که به انتخاب و بر حسب نیاز می توان آنها را مدیریت کرد.
</p>

<p dir="rtl">
    ابتدا فایل app.js را بعد از تگ body صفحه ارسال پیام وارد کنید.
    سپس فرم ارسال پیام و دکمه ی ارسال را تعریف کنید همچنین تگ meta را درون تگ head قرار دهید.
    این تگ برای ارسال csrf_token در درخواست های ajax استفاده می شود
</p>

```
<meta name="csrf-token" content="{{ csrf_token() }}">
```

<p dir="rtl">
    من از روش زیر برای ارسال پیام استفاده کردم. شما می توانید ارسال فایل و ... را با همین روش انجام دهید با اندکی تفاوت در دستور. 
    همچنین من receiver_id را برابر id کاربری که به آن پیام می دهم قرار دادم و هم به عنوان receiver_id در chat table استفاده می کنم و هم اینکه آن را به عنوان آی دی که قرار هست کاربر را احراز هویت کند به chat event پاس می دهم.
</p>

<p dir="rtl">
    در کد زیر من csrf_token را هم ارسال کرده ام و ارسال پیام را به صورت post انجام داده ام. شما می توانید route خود را داشته باشید در هر صورت باید آن را در web.php به کنترلر مربوط به چت متصل کنید.
</p>

```
$(document).ready(function(){
    $("#send").click(function () {
        var receiver = $("#receiver").val();
        var message = $("#message").val();
        axios.post('/chat/send', {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            receiver: receiver,
            message: message
        }).catch(function (error) {
            console.log(error);
        });
    });
});
```

<br><br>

<h3 dir="rtl">در controller جه می گذرد؟ </h3>

<p dir="rtl">
    فرض کنید ما ChatController را ساخته ایم. اکنون در متد send آن پیام را دریافت می کنیم و آن را به ChatEvent می سپاریم.
</p>

<p dir="rtl">
    در متد send یک پیام ایجاد می کنیم و آن را درون متغییر ذخیره می کنیم. سپس ChatEvent را فراخوانی کرده و متغییر chat را به آن پاس می دهیم. اگر به صورت private استفاده می کنید همانند کد پایین receiver_id را به عنوان پارامتر دوم پاس دهید در غیر این صورت از این کار صرف نظر کنید.
</p>

```
public function send(){
    $chat=auth()->user()->chats()->create([
        'receiver'=>request()->receiver,
        'message'=>request()->message
    ]);
    broadcast(new ChatEvent($chat , $chat->receiver));
}
```
<br><br>

<h3 dir="rtl"> گام آخر و دریافت پیام </h3>

<p dir="rtl">
    به view چت بر می گردیم و با کد زیر id کاربر آنلاین را به صورت قابل استفاده در جاوااسکریپت دریافت می کنیم.
</p>

```
<script>
    window.Laravel = {!! json_encode([
        'id' => auth() -> check() ? auth() -> user() -> id : null,
    ])!!};
</script>
```

<p dir="rtl">
    حالا با دستور زیر و با استفاده از larave echo نام channel کاربر آنلاین شده را تعیین کرده و آن را listen می کنیم که هروقت پیامی ارسال شد بتوان مشاهده کرد. 
</p>

<p dir="rtl">
    نکته: در این پروژه من پیام را لاگ کرده و درون console مرورگر نمایش می دهم شما می توانید آن را درون مسیج باکس یا هرجا استفاده کنید.
</p>

```
Echo.private('chat.' + window.Laravel.id).listen('ChatEvent', (e) => {
    console.log(e);
});
```

<p dir="rtl">
    اگر به صورت public استفاده می کنید باید به صورت زیر عمل کنید.
</p>

```
Echo.channel('chat').listen('ChatEvent', (e) => {
    console.log(e);
});
```
<p dir="rtl">
    برای نمایش نوتیفیکیشن یا هر چیز دیگر هم می توان با کمی تغییر و خلاقیت به همین روش عمل کرد.
</p>

<p dir="rtl">
    امیدوارم که این آموزش مفید واقع شده باشد.
</p>
