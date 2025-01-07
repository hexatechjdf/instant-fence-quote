<?php

use App\Http\Controllers\Api\FenceFtAvailableController;
use App\Http\Controllers\Api\FtAvailableController;
use App\Http\Controllers\Api\FenceController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

header('Access-Control-Allow-Origin: *');
Route::post('/webhook/user', function () {
    $data = file_get_contents('php://input');
    if (!empty($data)) {
        \DB::table('webhook_calls')->insert(['response' => $data]);
    }

    echo '1';
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/fences', [FenceController::class, 'fences']);
Route::get('/ft-availables', [FtAvailableController::class, 'ftAvailables']);
Route::get('/fence-ft', [FenceFtAvailableController::class, 'fenceFt']);

Route::get('/all/{location?}', [FenceController::class, 'getAllWithLocation']);
Route::get('/all/{id?}', [FenceController::class, 'allAllWithId']);

Route::get('/fence-with-single-price', [FenceController::class, 'singleFencePrice']);
Route::get('/fence-with-double-price', [FenceController::class, 'doubleFencePrice']);

//send webhook
Route::post('/webhook/send', [EstimateController::class, 'sendWebhook']);


//create products
Route::post('/automatic-user-creation', [WebhookController::class, 'createProduct']);
