<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLeadController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProductImageController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Home', [
    'products' => Product::orderBy('id')->get(),
    'seo' => [
        'title' => 'VOLGASHOT - Продажа Производство и продажа оружейной Дроби, Картечи свинцовой охотничьей.',
        'canonical' => route('home'),
        'description' => 'Наше производство специализируется на продаже оружейной дроби высокого качества для различных видов стрелкового оружия. Мы предлагаем широкий ассортимент дроби и картечи от производителя, что позволяет нашим клиентам выбрать подходящий вариант для своих нужд.',
    ],
]))->name('home');
Route::get('/robots.txt', fn () => response(
    "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /login\nDisallow: /register\n\nSitemap: ".url('/sitemap.xml')."\n",
    200,
    ['Content-Type' => 'text/plain; charset=UTF-8'],
))->name('robots');
Route::get('/sitemap.xml', fn () => response(
    '<?xml version="1.0" encoding="UTF-8"?>'.
    '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.
    '<url><loc>'.e(route('home')).'</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>'.
    '</urlset>',
    200,
    ['Content-Type' => 'application/xml; charset=UTF-8'],
))->name('sitemap');
Route::post('/zayavki', [LeadController::class, 'store'])->middleware('throttle:10,1')->name('leads.store');
Route::get('/product-images/{product}', [ProductImageController::class, 'show'])->name('products.photo');

Route::middleware(['auth', 'approved'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::post('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/leads/{lead}', [AdminLeadController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [AdminLeadController::class, 'destroy'])->name('leads.destroy');
    Route::patch('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

Route::redirect('/dashboard', '/admin')->middleware('auth')->name('dashboard');
