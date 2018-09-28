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

if (env('APP_ENV') === 'production') {
    \URL::forceScheme('https'); 
}

Route::get('/', function () {
    return view('welcome');
})->middleware('guest')->name('welcome');

Auth::routes();

Route::group(['middleware'=>'auth'], function() {
    Route::get('/home', 'FileStoreController@getFiles')->name('home');
    Route::get('/file_upload', function() { return redirect()->route('home'); });
    Route::post('/file_upload', 'FileStoreController@addFile')->name('file.upload');
    Route::get('/file_download/{file_id}', function() { return redirect()->route('home')->with(['alert' => 'Downloading from browser is not allowed!']); });
    Route::post('/file_download/{file_id}', 'FileStoreController@downloadFile')->name('file.download');
    Route::get('/file_delete/{file_id}', function() { return redirect()->route('home'); });
    Route::delete('/file_delete/{file_id}', 'FileStoreController@deleteFile')->name('file.delete');
    Route::get('/file_preview/{file_id}', function() { return redirect()->route('home')->with(['alert' => 'Downloading from browser is not allowed!']); });
    Route::post('/file_preview/{file_id}', 'FileStoreController@previewFile')->name('file.preview');
});

