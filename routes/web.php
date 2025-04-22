<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Welcome;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Dashboard;
use App\Livewire\ProductList;
use App\Livewire\MessageBox;
use App\Livewire\Booking;
use App\Livewire\BookingSuccess;
use App\Livewire\ProductManager;
use App\Livewire\ProductShow;
use App\Livewire\LikedProducts;
use App\Livewire\ShelfShow;
use App\Livewire\ShelfRentalShow;
use App\Livewire\WishlistShow;
use App\Livewire\Pages\TermsAndConditions;
use App\Livewire\Pages\PrivacyPolicy;
use App\Livewire\Pages\Imprint;
use App\Livewire\Pages\Prices;
use App\Livewire\Pages\HowTo;
use App\Livewire\Pages\AboutUs;
use App\Livewire\Pages\Contact;
use App\Livewire\Pages\Faqs;
use App\Livewire\Pages\Sitemap;
use App\Livewire\Pages\Jobs;
use App\Livewire\Customer\ExtendShelfRental;



use App\Livewire\Customer\Rating\ClaimRatingSuccess;






use App\Livewire\Auth\RequestPasswordResetLink;
use App\Livewire\Auth\ResetPassword;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\PayPalController;




    // Routen für alle
    Route::get('/', Welcome::class)->name('home');
    Route::get('/booking', Booking::class)->name('booking');
    Route::get('/booking/success/{shelfRentalId}', BookingSuccess::class)->name('booking.success');
    Route::get('/products', ProductList::class)->name('products');
    Route::get('/product/{id}', ProductShow::class)->name('product.show');
    Route::get('/shelf/{shelfRentalId}', ShelfShow::class)->name('shelf.show');
    Route::get('/prices', Prices::class)->name('prices');
    Route::get('/howto', HowTo::class)->name('howto');
    Route::get('/aboutus', AboutUs::class)->name('aboutus');
    Route::get('/faqs', Faqs::class)->name('faqs');
    Route::get('/termsandconditions', TermsAndConditions::class)->name('terms');
    Route::get('/imprint', Imprint::class)->name('imprint');
    Route::get('/privacypolicy', PrivacyPolicy::class)->name('privacypolicy');
    Route::get('/contact', Contact::class)->name('contact');
    Route::get('/sitemap', Sitemap::class)->name('sitemap');
    Route::get('/wishlist/{userId}', WishlistShow::class)->name('wishlist.show');
    Route::get('/claim-rating/success/{hash}', ClaimRatingSuccess::class)->name('claim-rating.success');




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

    Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    

        // Customer Routes
        Route::middleware(['role:guest'])->group(function () {
            Route::get('/dashboard', Dashboard::class)->name('dashboard');
            Route::get('/messages', MessageBox::class)->name('messages');
            Route::get('/shelf-rental/{shelfRentalId}', ShelfRentalShow::class)->name('shelfrental.show');
            Route::get('/{shelfRental}/product/create', ProductManager::class)->name('product.create');
            Route::get('/product/edit/{product}', ProductManager::class)->name('product.edit');
            Route::get('/liked-products', LikedProducts::class)->name('liked.products');
            Route::get('/customer/shelf-rental/{rentalId}/extend', ExtendShelfRental::class)->name('customer.shelf-rental.extend');
        });

    });


    Route::get('/download/invoice/{filename}', function ($filename) {
        // Logge den Dateinamen
        Log::info("Download angefordert für Datei: {$filename}");
    
        $filePath = "private/bills/{$filename}";
    
        // Prüfe, ob die Datei existiert
        if (!Storage::disk('local')->exists($filePath)) {
            Log::warning("Datei nicht gefunden: {$filePath}");
            abort(404, 'Datei nicht gefunden.');
        }
    
        // Zugriffsbeschränkung prüfen
        if (!auth()->check()) {
            Log::warning("Nicht authentifizierter Zugriff auf Datei: {$filePath}");
            abort(403, 'Zugriff verweigert.');
        }
    
        if (!auth()->user()->hasAccessToInvoice($filename)) {
            Log::warning("Benutzer hat keinen Zugriff auf Datei: {$filePath}");
            abort(403, 'Zugriff verweigert.');
        }
    
        Log::info("Benutzer hat Zugriff. Datei wird bereitgestellt: {$filePath}");
    
        // Datei zurückgeben
        return Storage::disk('local')->download($filePath);
    })->name('invoice.download');
