<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', '\App\Http\Controllers\shop\IndexController@index');

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
//必须登录查看的路由
Route::prefix('/') -> middleware('checkLogin') -> group(function(){
    Route::get('/car', '\App\Http\Controllers\shop\CarController@car');
    Route::get('/user', '\App\Http\Controllers\shop\UserController@user')->name('user');
    Route::get('/pay/{goods_id}', '\App\Http\Controllers\shop\PayController@pay')->name('pay');
    Route::get('/success/{order_id}', '\App\Http\Controllers\shop\OrderController@success')->name('success');
    Route::get('/aliPay/{order_id}', '\App\Http\Controllers\shop\AliPayController@aliPay')->name('aliPay');
    Route::get('/editAddress/{address_id?}', '\App\Http\Controllers\shop\AddressController@editAddress')->name('editAddress');
    Route::get('/order/{type?}', '\App\Http\Controllers\shop\OrderController@order')->name('order');
    Route::get('/addressInfo', '\App\Http\Controllers\shop\AddressController@addressInfo')->name('addressInfo');
    Route::get('/paySuccess', '\App\Http\Controllers\shop\AliPayController@paySuccess')->name('paySuccess');
    Route::get('/address', '\App\Http\Controllers\shop\AddressController@address')->name('address');
    Route::get('/ticket', '\App\Http\Controllers\shop\TicketController@ticket')->name('ticket');
    Route::get('/collect', '\App\Http\Controllers\shop\UserController@collect')->name('collects');

    Route::post('/doAddress', '\App\Http\Controllers\shop\AddressController@doAddress')->name('doAddress');
    Route::post('/doEditAddress', '\App\Http\Controllers\shop\AddressController@doEditAddress')->name('doEditAddress');
    
});

Route::get('/prolist/{cate_id?}', '\App\Http\Controllers\shop\ProListController@proList')->name('prolist');
Route::get('/index', '\App\Http\Controllers\shop\IndexController@index');
Route::get('/login', '\App\Http\Controllers\shop\LoginController@login')->name('login');
Route::get('/reg', '\App\Http\Controllers\shop\LoginController@reg');
Route::get('/proInfo/{goods_id}', '\App\Http\Controllers\shop\ProListController@proInfo')->name('proInfo');
Route::get('/unLogin', '\App\Http\Controllers\shop\LoginController@unLogin')->name('unLogin');

Route::post('/doReg', '\App\Http\Controllers\shop\LoginController@doReg');
Route::post('/doLogin', '\App\Http\Controllers\shop\LoginController@doLogin');

// Route::get('/test', '\App\Http\Controllers\shop\LoginController@test');
// Route::get('/coo', '\App\Http\Controllers\shop\LoginController@coo');

Route::prefix('ajax') -> group(function(){
    Route::post('checkEmail','\App\Http\Controllers\shop\LoginController@checkEmail');
    Route::post('proList','\App\Http\Controllers\shop\ProListController@ajaxProList')->name('ajaxPriList');
    Route::post('addBuy','\App\Http\Controllers\shop\ProListController@ajaxAddBuy')->name('addBuy');
    Route::post('changeNum','\App\Http\Controllers\shop\CarController@ajaxChangeNum')->name('changeNum');
    Route::post('sumAll','\App\Http\Controllers\shop\CarController@ajaxSumAll')->name('sumAll');
    Route::post('subBuy','\App\Http\Controllers\shop\PayController@ajaxSubBuy')->name('subBuy');
    Route::post('getCountry','\App\Http\Controllers\shop\AddressController@ajaxGetCountry')->name('getCountry');
    Route::post('subOrder','\App\Http\Controllers\shop\OrderController@ajaxSubOrder')->name('subOrder');
    Route::post('delOrder','\App\Http\Controllers\shop\OrderController@ajaxDelOrder')->name('delOrder');
    Route::post('getOrderType','\App\Http\Controllers\shop\OrderController@order')->name('getOrderType');
    Route::post('collect','\App\Http\Controllers\shop\ProListController@ajaxCollect')->name('collect');
    Route::post('moreProList','\App\Http\Controllers\shop\ProListController@ajaxMoreProList')->name('moreProList');
    Route::post('delCollect','\App\Http\Controllers\shop\UserController@ajaxDelCollect')->name('delCollect');
});

//注册登录
Route::prefix('reg') -> group(function(){
    Route::get('register','\App\Http\Controllers\register\RegisterController@reg');
    Route::post('doRegister','\App\Http\Controllers\register\RegisterController@doReg');
    Route::post('email','\App\Http\Controllers\register\RegisterController@email')->name('email');
    Route::get('logins','\App\Http\Controllers\register\RegisterController@logins')->name('logins');
    Route::post('doLogin','\App\Http\Controllers\register\RegisterController@doLogin')->name('doLogin');
    Route::get('index','\App\Http\Controllers\register\RegisterController@index');
});
//支付宝异步通知 电脑
Route::post('/pay/notify_url','\App\Http\Controllers\shop\AliPayController@notify_url');
//支付宝付款 手机
// Route::get('/aliPay/{order_id}','\App\Http\Controllers\shop\WebController@return_url')->name('aliPay');
//支付宝同步跳转 手机
// Route::get('/paySuccess', '\App\Http\Controllers\shop\WebController@paySuccess')->name('paySuccess');
//支付宝异步通知 手机
// Route::post('/pay/notify_url','\App\Http\Controllers\shop\WebController@notify_url');

//微信公众号
Route::prefix('weChat') -> group(function(){
    
    Route::any('index','weChat\\IndexController@valid');
    
});

//后台管理微信平台
Route::prefix('admin') -> middleware('wxCheckLogin') -> group(function(){
    Route::get('index','weChat\\AdminController@index')->name('wxIndex');
    Route::get('subscribe','weChat\\AdminController@subscribe')->name('subscribe');
    Route::post('addSubscribe','weChat\\AdminController@addSubscribe')->name('addSubscribe');
    Route::get('getToken','weChat\\AdminController@getToken')->name('getToken');
    Route::get('doAdd','weChat\\AdminController@doAdd')->name('wxDoAdd');
    Route::get('keywords','weChat\\AdminController@keywords')->name('keywords');
    Route::get('wordsList','weChat\\AdminController@wordsList')->name('wordsList');
    Route::post('addKeywords','weChat\\AdminController@addKeywords')->name('addKeywords');
    Route::post('addFile','weChat\\AdminController@addFile')->name('addFile');
    Route::post('uploadNews','weChat\\AdminController@uploadNews')->name('uploadNews');
    Route::post('uploadFile','weChat\\AdminController@uploadFile')->name('uploadFile');
    Route::get('subscribeList','weChat\\AdminController@subscribeList')->name('subscribeList');
    Route::post('changeResponseType','weChat\\AdminController@changeResponseType')->name('changeResponseType');
    Route::any('subscribeJson','weChat\\AdminController@subscribeJson')->name('subscribeJson');
    Route::any('uploadVideo','weChat\\AdminController@uploadVideo')->name('uploadVideo');
    Route::post('delMaterial','weChat\\AdminController@delMaterial')->name('delMaterial');
    Route::get('addMaterial','weChat\\AdminController@addMaterial')->name('addMaterial');
    Route::post('uploadFileMaterial','weChat\\AdminController@uploadFileMaterial')->name('uploadFileMaterial');
    Route::post('editMaterial','weChat\\AdminController@editMaterial')->name('editMaterial');
    Route::get('addCity','weChat\\AdminController@addCity')->name('addCity');
    Route::get('userList','weChat\\AdminController@userList')->name('userList');
    Route::any('getUserList','weChat\\AdminController@getUserList')->name('getUserList');
    Route::any('sendGroupMsg','weChat\\AdminController@sendGroupMsg')->name('sendGroupMsg');
    Route::get('alertGroupMsg/{id?}/{type?}','weChat\\AdminController@alertGroupMsg')->name('alertGroupMsg');
    Route::get('tagList','weChat\\GroupController@tagList')->name('tagList');
    Route::get('createTag','weChat\\GroupController@createTag')->name('createTag');
    Route::post('doCreateTag','weChat\\GroupController@doCreateTag')->name('doCreateTag');
    Route::get('selectTag/{id?}','weChat\\GroupController@selectTag')->name('selectTag');
    Route::post('doSelectTag','weChat\\GroupController@doSelectTag')->name('doSelectTag');
    Route::any('fansList','weChat\\GroupController@fansList')->name('fansList');
    Route::any('getJsonFans','weChat\\GroupController@getJsonFans')->name('getJsonFans');
    Route::post('delTag','weChat\\GroupController@delTag')->name('delTag');
    Route::get('createMenu','weChat\\MenuController@createMenu')->name('createMenu');
    Route::post('doCreateMenu','weChat\\MenuController@doCreateMenu')->name('doCreateMenu');
    Route::get('MenuList','weChat\\MenuController@MenuList')->name('MenuList');
    Route::get('editMenu/{id?}','weChat\\MenuController@editMenu')->name('editMenu');
    Route::post('doEditMenu','weChat\\MenuController@doEditMenu')->name('doEditMenu');
    Route::post('delMenu','weChat\\MenuController@delMenu')->name('delMenu');
    Route::post('addMenu','weChat\\MenuController@addMenu')->name('addMenu');
    Route::get('alertPersonMenu','weChat\\MenuController@alertPersonMenu')->name('alertPersonMenu');
    Route::post('doCreatePersonMenu','weChat\\MenuController@doCreatePersonMenu')->name('doCreatePersonMenu');
    Route::get('personMenuList','weChat\\MenuController@personMenuList')->name('personMenuList');
    Route::any('getJsonMenu','weChat\\MenuController@getJsonMenu')->name('getJsonMenu');
    Route::post('delPersonMenu','weChat\\MenuController@delPersonMenu')->name('delPersonMenu');
    Route::get('createQrCode','weChat\\QrCodeController@createQrCode')->name('createQrCode');


    
});

Route::get('admin/login','weChat\\AdminController@login')->name('wxLogin');
Route::post('admin/doLogin','weChat\\AdminController@doLogin')->name('wxDoLogin');

//获取微信永久素材
Route::get('admin/getRemoteMaterial','weChat\\AdminController@getRemoteMaterial')->name('getRemoteMaterial');
//获取微信关注的所有用户oppenid
Route::get('admin/getRemoteUser','weChat\\AdminController@getRemoteUser')->name('getRemoteUser');

//清空Redis中的access_token
Route::get('admin/clearToken','weChat\\AdminController@clearToken');

//获取微信授权信息
Route::get('weChatLogin/{uniqid?}','weChat\\AdminController@weChatLogin')->name('weChatLogin');
//绑定商城登陆
Route::get('binding','weChat\\AdminController@binding')->name('binding');
//发送验证码
Route::post('admin/sendCode','weChat\\AdminController@sendCode')->name('sendCode');
Route::post('admin/doBinding','weChat\\AdminController@doBinding')->name('doBinding');
Route::get('admin/getWxUserInfo','weChat\\AdminController@getWxUserInfo')->name('getWxUserInfo');

//获取扫码的状态
Route::post('getUniqidStatus','weChat\\AdminController@getUniqidStatus')->name('getUniqidStatus');

