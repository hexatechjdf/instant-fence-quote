<?php
header('X-Frame-Options: ALLOWALL');
header('Access-Control-Allow-Origin: *');

use App\Helpers\CRM;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomFieldController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FenceController;
use App\Http\Controllers\Admin\FtAvailableController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FenceFtAvailableController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ExtraPageController;
use App\Http\Controllers\CustomPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CRMConnectionController;
use App\Jobs\AgencyUserAutoAuth;
use App\Jobs\GetLocationAccessToken;
use App\Jobs\InCompleteEstimatesTriger;
use App\Jobs\LocationUserAutoAuth;
use App\Jobs\ProcessRefreshToken;
use App\Jobs\TriggerCustomField;
use App\Models\User;
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

Route::get('/migrate', function () {
    // \Artisan::call('migrate');
});


Route::get('/process-code', function () {

    return 'final';
    $hashedValue = bcrypt('12345678');
    // GetLocationAccessToken::dispatch(2, 'JoqQ51Bl3LEmR42l6LrG', 'viaAgency')->onQueue(env('JOB_QUEUE_TYPE'));

    return $hashedValue;


    // $companies = User::where(['role' => 0, 'is_active' => true])->get();
    // foreach ($companies as $company) {
    //     //$estimates = Estimate::where([['is_completed', '=', false], ['created_at', '>=', Carbon::now()->subMinutes(25)->format('Y-m-d H:i:s')]])->where('company_id', $company->id);
    //     $estimates = Estimate::where('company_id', $company->id)->latest()->take(5);

    //         if (check_ghl($company)) {
    //             $estimates = $estimates->get();
    //             //Job again
    //             foreach ($estimates as $estimate) {
    //                 if($estimate->contact_id !== null && $estimate->contact_id !== '' && !empty($estimate->contact_id)){
    //                     SendOneEstimate::dispatch($estimate, $company->location, $company->id)->onQueue(env('JOB_QUEUE_TYPE'));
    //                     return 'done';
    //                 }
    //             }
    //         }
    //     }

    // exit;

    // $user_id = 1;
    // AutoAuthUser::dispatch($user_id)->onQueue(env('JOB_QUEUE_TYPE'));
    // return 'Job Run';

//     $res = CRM::crmV2Loc(164, 'HuVkfWx59Pv4mUMgGRTp', 'contacts?limit=100', 'get');
// dd($res);

    $adminUserId = 1;
    $urlmain = 'locations/search';
    $userIds = User::where(['role'=> 0, 'is_active' => 1, 'separate_location' => 0])->pluck('location', 'id')->toArray();
    $locations = CRM::agencyV2($adminUserId, $urlmain);

    if($locations && property_exists($locations, 'locations')){
        // $locations = json_decode($locations);
        $locations = $locations->locations;
        foreach ($locations as $key => $loc) {
            $locationId = $loc->id;
            foreach ($userIds as $currentUserId => $currentUserLoc) {
                if($locationId == $currentUserLoc){
                CRM::getLocationAccessToken(1, $locationId);
                // CRM::getLocationAccessToken($currentUserId, $locationId);
                }
            }
        }
        return 'Location Access Token Saved';
    }
    return 'Some thing went wrong';
});

Route::get('/cache-clear', function () {
    \Artisan::call('optimize:clear');
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
});

Route::get('/.well-known/acme-challenge/{file}', function ($file) {
    echo file_get_contents(__DIR__ . '/../.well-known/acme-challenge/' . $file);
});

Route::get('/scripts/{ext}/{file}', function ($ext, $file) {
    echo file_get_contents(__DIR__ . '/../public/assets/' . $ext . '/' . $file . '.' . $ext);
});




// Route::get('/', function () {
//     return view('welcome');
// });
require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {

    Route::get('/loginwith/{email}', function ($email) {
        $email = decrypt($email);
        $user = \Auth::user();
        if ($user->role == 1) {
            session()->put('super_admin', $user);
            $user = \App\Models\User::where(['email' => $email, 'role' => 0])->first();
            if ($user->role == 0) {
                // session()->remove('ghlcontacts');
                \Auth::login($user);
                return redirect()->route('dashboard');
            }
        }
    })->name('loginwith');

    Route::get('/backtoadmin', function () {
        if (session('super_admin') && !empty(session('super_admin'))) {
            Auth::login(session('super_admin'));
            session()->put('super_admin', '');
            return redirect()->route('user.list');
        }
    })->name('backadmin');


    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile-save', [DashboardController::class, 'general'])->name('profile.save');
    Route::post('/password-save', [DashboardController::class, 'password'])->name('password.save');

    Route::prefix('roles')->middleware('admin')->name('role.')->group(function () {
        Route::get('/list', [RoleController::class, 'list'])->name('list');
        Route::get('/add', [RoleController::class, 'add'])->name('add');
        Route::get('/edit/{id?}', [RoleController::class, 'edit'])->name('edit');
        Route::post('/save/{id?}', [RoleController::class, 'save'])->name('save');
        Route::get('/delete/{id?}', [RoleController::class, 'delete'])->name('delete');
    });

    Route::prefix('users')->middleware('admin')->name('user.')->group(function () {
        Route::get('/list', [UserController::class, 'list'])->name('list');
        Route::get('/add', [UserController::class, 'add'])->name('add');
        Route::get('/edit/{id?}', [UserController::class, 'edit'])->name('edit');
        Route::post('/save/{id?}', [UserController::class, 'save'])->name('save');
        Route::get('/delete/{id?}', [UserController::class, 'delete'])->name('delete');
        Route::get('/pendings/{id?}', [UserController::class, 'pending'])->name('pending');
        Route::get('/is-active/{id?}', [UserController::class, 'isActive'])->name('is-active');
        Route::get('/approve-all', [UserController::class, 'approveAll'])->name('approve-all');
        Route::get('/delete-all', [UserController::class, 'deleteAll'])->name('delete-all');
        Route::get('/manage-subaccount', [UserController::class, 'manageSubaccount'])->name('manage-subaccount');
        Route::post('/connect-locations', [UserController::class, 'connectLocations'])->name('connect-locations');
        Route::post('/save-user-detail', [UserController::class, 'saveUserDetail'])->name('save-user-detail');

        // Route::get('/user/locations', [UserController::class, 'getLocations'])->name('get-locations');


    });

    Route::prefix('categories')->middleware('user')->name('category.')->group(function () {
        Route::get('/list', [CategoryController::class, 'list'])->name('list');
        Route::get('/add', [CategoryController::class, 'add'])->name('add');
        Route::get('/edit/{id?}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/save/{id?}', [CategoryController::class, 'save'])->name('save');
        Route::get('/delete/{id?}', [CategoryController::class, 'delete'])->name('delete');
        Route::get('/status/{id?}', [CategoryController::class, 'status'])->name('status');
    });

    Route::prefix('custom-fields')->middleware('admin')->name('custom.')->group(function () {
        Route::get('/list', [CustomFieldController::class, 'list'])->name('list');
        Route::get('/add', [CustomFieldController::class, 'add'])->name('add');
        Route::get('/edit/{id?}', [CustomFieldController::class, 'edit'])->name('edit');
        Route::post('/save/{id?}', [CustomFieldController::class, 'save'])->name('save');
        Route::get('/delete/{id?}', [CustomFieldController::class, 'delete'])->name('delete');
    });

    Route::prefix('ft-availables')->middleware('user')->name('ft_available.')->group(function () {
        Route::get('/list', [FtAvailableController::class, 'list'])->name('list');
        Route::get('/add', [FtAvailableController::class, 'add'])->name('add');
        Route::get('/edit/{id?}', [FtAvailableController::class, 'edit'])->name('edit');
        Route::post('/save/{id?}', [FtAvailableController::class, 'save'])->name('save');
        Route::get('/delete/{id?}', [FtAvailableController::class, 'delete'])->name('delete');
        Route::get('/is-active/{id?}', [FtAvailableController::class, 'isActive'])->name('is-active');
        Route::get('/is-available/{id?}', [FtAvailableController::class, 'isAvailable'])->name('is-available');
    });

    Route::prefix('fences')->middleware('user')->name('fence.')->group(function () {
        Route::get('/list', [FenceController::class, 'list'])->name('list');
        Route::get('/add', [FenceController::class, 'add'])->name('add');
        Route::get('/edit/{id?}', [FenceController::class, 'edit'])->name('edit');
        Route::post('/save/{id?}', [FenceController::class, 'save'])->name('save');
        Route::get('/delete/{id?}', [FenceController::class, 'delete'])->name('delete');
        Route::get('/status/{id?}', [FenceController::class, 'status'])->name('status');
        Route::get('/price-fit/{id?}', [FenceController::class, 'priceFit'])->name('price');
        Route::post('/price-save/{id?}', [FenceController::class, 'priceSave'])->name('price.save');
        Route::post('/fencetype-price', [FenceController::class, 'fenceTypePrice'])->name('fencetype.price');
    });

    Route::prefix('fences-ft-availables')->middleware('admin')->name('fence-ft.')->group(function () {
        Route::get('/fence-ft-availabe', [FenceFtAvailableController::class, 'list'])->name('list');
    });

    Route::prefix('settings')->middleware('auth')->name('setting.')->group(function () {
        Route::get('Site-Settings', [SettingController::class, 'siteSettings'])->name('index');
        Route::post('Site-Settings', [SettingController::class, 'siteSettingsSave'])->name('save');
    });

    //products
    Route::prefix('products')->name('product.')->group(function () {
        Route::get('/list', [ProductController::class, 'list'])->name('list');
        Route::get('/add', [ProductController::class, 'add'])->name('add');
        Route::get('/edit/{id?}', [ProductController::class, 'edit'])->name('edit');
        Route::post('/save/{id?}', [ProductController::class, 'save'])->name('save');
        Route::get('/delete/{id?}', [ProductController::class, 'delete'])->name('delete');
        Route::get('/manage-permissions/{id?}', [ProductController::class, 'manage'])->name('manage');
        Route::post('/save-permissions/{id?}', [ProductController::class, 'savePermission'])->name('permission.save');
    });

    //custom pages
    Route::prefix('custom-pages')->name('custom-pages.')->group(function () {
        Route::get('/list', [CustomPageController::class, 'customPageList'])->name('list');
        Route::get('/add', [CustomPageController::class, 'customPageAdd'])->name('add');
        Route::get('/edit/{id?}', [CustomPageController::class, 'customPageEdit'])->name('edit');
        Route::post('/save/{id?}', [CustomPageController::class, 'savePage'])->name('save');
        Route::get('/delete/{id?}', [CustomPageController::class, 'customPageDelete'])->name('delete');
        Route::get('/visit/{id?}', [CustomPageController::class, 'customPageVisit'])->name('visit');
        Route::get('/status/{id?}', [CustomPageController::class, 'customPageStatus'])->name('status');
        Route::get('/view/{slug?}', [CustomPageController::class, 'customPagePublic'])->name('public');
    });
});

Route::prefix('estimator')->name('estimator.')->group(function () {
    Route::get('/survey/{id?}', [SettingController::class, 'estimator'])->name('index');
    Route::get('/save/{id?}', [SettingController::class, 'estimatorSave'])->name('save');
    Route::get('/save_contact/{id?}', [SettingController::class, 'saveContact'])->name('saveContact');
    Route::get('/thank-you/{id?}', [SettingController::class, 'thankYou'])->name('thank-you');
});

Route::prefix('settings/white-label')->middleware('auth')->name('white-label.')->group(function () {
    Route::get('/', [SettingController::class, 'whiteLabel'])->name('index');
    Route::post('/save', [SettingController::class, 'saveDomain'])->name('save');
});

Route::prefix('custom/css')->middleware('auth')->name('custom-css.')->group(function () {
    Route::get('/', [SettingController::class, 'customCss'])->name('index');
});

Route::prefix('estimates')->middleware('auth')->name('estimate.')->group(function () {
    Route::get('/', [EstimateController::class, 'Estimates'])->name('index');
});

Route::prefix('estimates')->middleware('auth')->name('estimate.')->group(function () {
    Route::get('/', [EstimateController::class, 'Estimates'])->name('index');
    Route::get('/resend/{id}', [EstimateController::class, 'resendEstimates'])->name('resend');
    Route::get('/delete/{id?}', [EstimateController::class, 'deleteEstimates'])->name('delete');
});

Route::get('/send-step/{id?}', [EstimateController::class, 'saveSteps'])->name('estimate.saveEachStep');
Route::post('estimator/save/image', [EstimateController::class, 'saveImage'])->name('estimate.save.image');




//extra pages
Route::prefix('pages')->name('extra-page.')->group(function () {
    Route::get('/terms', [ExtraPageController::class, 'terms'])->name('term');
    Route::get('/terms-of-services', [ExtraPageController::class, 'termOfService'])->name('term-of-service');
});

//Send data in the Job work here
// Route::get('/cron/incomplete-estimates',[EstimateController::class,'InCompleteEstimates']);

Route::get('/cron/incomplete-estimates', function () {
    InCompleteEstimatesTriger::dispatch()->onQueue(env('JOB_QUEUE_TYPE'));
});

Route::get('/cron/set-customfields', function () {
    TriggerCustomField::dispatch()->onQueue(env('JOB_QUEUE_TYPE'));
});

Route::get('/cron-jobs/process_refresh_token', function () {
    ProcessRefreshToken::dispatch()->onQueue(env('JOB_QUEUE_TYPE'));
    // dispatch((new CheckCustomValuesJob('HuVkfWx59Pv4mUMgGRTp', 9)));
    // return response()->json(['success' => 'We are matching custom values']);
});


//Mo need of it now
Route::get('/cron/auto-auth-user', function () {
    AgencyUserAutoAuth::dispatch()->onQueue(env('JOB_QUEUE_TYPE'));
    LocationUserAutoAuth::dispatch()->onQueue(env('JOB_QUEUE_TYPE'));
});

Route::prefix('authorization')->name('crm.')->group(function () {
    Route::get('/crm/oauth/callback', [CRMConnectionController::class, 'crmCallback'])->name('oauth_callback');
});
