<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Participant\Promotion\TicketQrController;
use App\Http\Controllers\PublicFormController;
use App\Http\Middleware\PromotionPrivacyHeaders;
use App\Livewire\Articles\Blog\BlogList;
use App\Livewire\Articles\Blog\BlogShow;
use App\Livewire\Articles\News\NewsList;
use App\Livewire\Articles\News\NewsShow;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\RequestPasswordResetLink;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\ClaimRatings\ClaimRatingShow;
use App\Livewire\Customer\Rating\ClaimRatingSuccess;
use App\Livewire\Dashboard;
use App\Livewire\Insurance\ShowInsurance;
use App\Livewire\Insurance\ShowSubtype;
use App\Livewire\MessageBox;
use App\Livewire\Pages\AboutUs;
use App\Livewire\Pages\Contact;
use App\Livewire\Pages\Faqs;
use App\Livewire\Pages\Guidance;
use App\Livewire\Pages\HowTo;
use App\Livewire\Pages\Imprint;
use App\Livewire\Pages\Insurances;
use App\Livewire\Pages\Premium;
use App\Livewire\Pages\PrivacyPolicy;
use App\Livewire\Pages\Ranking;
use App\Livewire\Pages\Reviews;
use App\Livewire\Pages\Sitemap;
use App\Livewire\Pages\TermsAndConditions;
use App\Livewire\Participant\Promotion\WheelLanding;
use App\Livewire\Profile\ClaimRating\ShowClaimRating;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

// Routen für alle
Route::get('/', Welcome::class)->name('home');
Route::get('/howto', HowTo::class)->name('howto');
Route::get('/aboutus', AboutUs::class)->name('aboutus');
Route::get('/faqs', Faqs::class)->name('faqs');
Route::get('/termsandconditions', TermsAndConditions::class)->name('terms');
Route::get('/imprint', Imprint::class)->name('imprint');
Route::get('/privacypolicy', PrivacyPolicy::class)->name('privacypolicy');
Route::get('/contact', Contact::class)->name('contact');
Route::get('/sitemap', Sitemap::class)->name('sitemap');
Route::get('/claim-rating/claim-rating-success/{hash}', ClaimRatingSuccess::class)->name('claim-rating.success');
Route::get('/insurances', Insurances::class)->name('insurances');
Route::get('/reviews', Reviews::class)->name('reviews');
Route::get('/ranking', Ranking::class)->name('ranking');
Route::get('/abos', Premium::class)->name('abos');
Route::get('/insurance/{insurance}', ShowInsurance::class)->name('insurance.show-insurance');
Route::get('/insurancetype/{insuranceSubtype}', ShowSubtype::class)->name('insurance.show-subtype');
Route::get('/review/{claimRating}', ClaimRatingShow::class)->name('review.show');
Route::get('/guidance', Guidance::class)->name('guidance');

Route::get('/blog', BlogList::class)->name('blog.index');
Route::get('/posts/{post}', BlogShow::class)->name('post.show');
Route::get('/news', NewsList::class)->name('news.index');
Route::get('/news/{post:slug}', NewsShow::class)->name('news.show');

Route::post('/form-submit', [PublicFormController::class, 'handle'])->name('form.submit');

Route::get('/gluecksrad', WheelLanding::class)
    ->middleware(PromotionPrivacyHeaders::class)
    ->name('promotion.wheel');

Route::middleware('guest')->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->whereIn('provider', ['google', 'apple'])
        ->name('social.redirect');
    Route::match(['get', 'post'], '/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->whereIn('provider', ['google', 'apple'])
        ->name('social.callback');
});

Route::get('/forgot-password', RequestPasswordResetLink::class)->name('password.request');
// Route::post('/forgot-password', [RequestPasswordResetLink::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
// Route::post('/reset-password', [ResetPassword::class, 'reset'])->name('password.update');
// Überschreibe die Standard-POST-Routen
Route::post('/forgot-password', function () {
    abort(404);
})->name('password.email');

Route::post('/reset-password', function () {
    abort(404);
})->name('password.update');

// Allgemeine Routes für Gäste
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'account.active'])->group(function () {
    // Customer Routes
    Route::middleware(['role:guest'])->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::redirect('/profil', '/dashboard');
        Route::get('/messages', MessageBox::class)->name('messages');
        Route::get('/profil/ownreview/{claimRating}', ShowClaimRating::class)->name('profile.claim-rating.claim-rating-show');
    });

});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'account.active', 'promotion.enabled', PromotionPrivacyHeaders::class])->group(function () {
    Route::get('/gluecksrad/ticket/{participation:public_id}/qr.svg', TicketQrController::class)
        ->name('promotion.ticket.qr');
});
