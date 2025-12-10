<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Password;
use App\Models\PendingPayment;

// User Facing Controllers
use App\Http\Controllers\SplashController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\UserMatchController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\FirebaseController;

// ** পরিবর্তিত অংশ: NotificationController এখন সরাসরি এখানে ইম্পোর্ট করা হচ্ছে **
use App\Http\Controllers\NotificationController;

// Admin Facing Controllers
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\AdminMatchController;
use App\Http\Controllers\Admin\RuleController as AdminRuleController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Guest User Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/', [SplashController::class, 'show'])->name('splash');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// --- Authenticated User Routes ---
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/matches/{type}', [MatchController::class, 'index'])->name('matches.list');
    Route::get('/match/{game}/{id}', [MatchController::class, 'showDetails'])->name('match.details');
    Route::post('/match/join/{id}', [MatchController::class, 'joinMatch'])->name('match.join');
    Route::get('/match/{id}/rules', [MatchController::class, 'rules'])->name('match.rules');
    Route::get('/my-matches', [UserMatchController::class, 'index'])->name('my.matches');
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/transaction', [WalletController::class, 'handleTransaction'])->name('wallet.transaction');
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/refer-and-earn', [ReferralController::class, 'index'])->name('referral.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/store-fcm-token', [FirebaseController::class, 'storeToken'])->name('store.token');
});

// --- External Redirects ---
Route::get('/developer-profile', fn() => redirect()->away('https://t.me/alfishahor'))->name('developer.profile');

// --- SMS GATEWAY APP NOTIFICATION ROUTE ---
Route::post('/sms-gateway-receiver', function (Request $request) {
    $secretApiKey = env('PAYMENT_API_SECRET_KEY', 'default_secret_key');
    if ($request->input('api_key') !== $secretApiKey) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized Access.'], 401);
    }
    $validator = Validator::make($request->all(), [
        'trx_id' => 'required|string|unique:pending_payments,trx_id',
        'amount' => 'required|numeric|min:1',
        'method' => 'required|string',
    ]);
    if ($validator->fails()) {
        return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
    }
    try {
        PendingPayment::create($request->only(['trx_id', 'amount', 'method']) + ['received_at' => now()]);
        return response()->json(['status' => 'success', 'message' => 'Payment data received and saved successfully.']);
    } catch (\Exception $e) {
        Log::error('SMS Gateway POST Error: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Database Error or Duplicate TrxID.'], 500);
    }
})->name('sms.gateway.post');

// =========================================================================
//                           ADMIN PANEL ROUTES
// =========================================================================
Route::prefix('adminlogin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::post('/change-password', [AdminProfileController::class, 'changePassword'])->name('password.change');
        
        Route::resource('users', AdminUserController::class)->names('users');
        Route::get('/transactions/{type}', [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::post('/transactions/{type}/{id}', [AdminTransactionController::class, 'update'])->name('transactions.update');
        
        Route::prefix('matches/{game_type}')->name('matches.')->group(function () {
            Route::get('/', [AdminMatchController::class, 'index'])->name('index');
            Route::get('/create', [AdminMatchController::class, 'create'])->name('create');
            Route::post('/', [AdminMatchController::class, 'store'])->name('store');
            Route::get('/{match}/edit', [AdminMatchController::class, 'edit'])->name('edit');
            Route::put('/{match}', [AdminMatchController::class, 'update'])->name('update');
            Route::delete('/{match}', [AdminMatchController::class, 'destroy'])->name('destroy');
            Route::get('/{match_key}/joiners', [AdminMatchController::class, 'showJoiners'])->name('joiners');
            Route::post('/{join_id}/refund', [AdminMatchController::class, 'refundPlayer'])->name('refund');
            Route::get('/{match_key}/result', [AdminMatchController::class, 'showResultForm'])->name('result.form');
            Route::post('/{match_key}/result', [AdminMatchController::class, 'processResult'])->name('result.process');
        });
        
        Route::get('/rules', [AdminRuleController::class, 'index'])->name('rules.index');
        Route::post('/rules', [AdminRuleController::class, 'update'])->name('rules.update');
        Route::resource('sliders', AdminSliderController::class)->names('sliders')->only(['index', 'store', 'destroy']);
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

        // ** পরিবর্তিত অংশ: NotificationController এখন সরাসরি কল করা হচ্ছে **
        Route::get('/send-notification', [NotificationController::class, 'showForm'])->name('notifications.form');
        Route::post('/send-notification', [NotificationController::class, 'sendNotification'])->name('notifications.send');
    });
});

// Fallback Route
Route::fallback(function () {
    return response()->view('errors.fallback', [], 404);
});
Route::get('/user/match/{id}/joiners', [MatchController::class, 'showJoiners'])
     ->name('user.matches.joiners');


// Forgot Password form
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Send Reset Link
Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// Reset Password form
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

// Reset Password submit
Route::post('/reset-password', function (Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');
