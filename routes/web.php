<?php

use Illuminate\Support\Facades\Route;
use NotificationChannels\Telegram\TelegramChannel;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;

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

Route::get('/', function () {
    return view('welcome');
});


/*
Notification::route('telegram', '1473863724')
            ->notify(new Telegram);

*/
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


/*
Route::get('sendEmail',function(){
    $job = (new SendEmailJob())->delay(Carbon::now()->addSeconds(5));
    dispatch($job);
    return 'email is sent properly';
});
*/