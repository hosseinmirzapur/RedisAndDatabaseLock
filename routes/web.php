<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    \Illuminate\Support\Facades\Redis::command('ZADD', ['links', 15, 'link1', 16, 'link2']);
    return response()->json([
        'links' => \Illuminate\Support\Facades\Redis::command('ZRANGE', ['links', 0, -1, 'WITHSCORES'])
    ]);
});
