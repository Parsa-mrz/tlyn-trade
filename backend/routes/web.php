<?php

use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', function () {
    return ResponseHelper::error ('Unauthorized',null,Response::HTTP_UNAUTHORIZED);
});
