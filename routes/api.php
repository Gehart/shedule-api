<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\ScheduleGettingController;
use App\Http\Controllers\ScheduleLoadController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/health-check', HealthCheckController::class . '@check');

$router->get('/test', ScheduleLoadController::class . '@test');

$router->get('/get-schedule', ScheduleGettingController::class . '@getSchedule');
