<?php

use Illuminate\Support\Facades\Route;

Route::get('/','ArticleController@index')->name('articles');
Route::resource('article' , 'ArticleController');
Route::post('preview' ,'ArticleController@preview' )->name('article.preview');

