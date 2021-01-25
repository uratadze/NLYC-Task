<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// article routes
Route::group(['prefix' => 'articles'], function () {

    Route::get('/', 'API\ArticleController@articles');

    Route::get('/{id}/comments', 'API\ArticleController@articleComments');

});

// tag routes
Route::group(['prefix' => 'tags'], function () {

    Route::get('/', 'API\TagController@tags');

    Route::get('/{id}/articles', 'API\TagController@tagArticle');

});

