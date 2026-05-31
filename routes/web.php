<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BlogTagController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ContactRequestController as AdminContactRequestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LawyerController;
use App\Http\Controllers\Admin\LegalLibraryItemController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SeoSettingController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TrainingCourseController as AdminTrainingCourseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Website\ConsultationController;
use App\Http\Controllers\Website\BlogController;
use App\Http\Controllers\Website\ContactRequestController;
use App\Http\Controllers\Website\LegalLibraryController;
use App\Http\Controllers\Website\TrainingCourseController;
use App\Models\Faq;
use App\Models\Client;
use App\Models\Lawyer;
use App\Models\Service;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    return view('website.index', [
        'services' => Schema::hasTable('services')
            ? Service::where('is_active', true)->orderBy('sort_order')->take(6)->get()
            : collect(),
        'clients' => Schema::hasTable('clients')
            ? Client::where('is_active', true)->orderBy('sort_order')->get()
            : collect(),
    ]);
})->name('home');


Route::get('/about', function () {
    return view('website.about', [
        'clients' => Schema::hasTable('clients')
            ? Client::where('is_active', true)->orderBy('sort_order')->get()
            : collect(),
    ]);
})->name('about');

Route::get('/contact', function () {
    return view('website.contact');
})->name('contact');

Route::get('/services', function () {
    return view('website.services', [
        'services' => Schema::hasTable('services')
            ? Service::where('is_active', true)->orderBy('sort_order')->get()
            : collect(),
    ]);
})->name('services');

Route::get('/services/{slug}', function (string $slug) {
    abort_unless(Schema::hasTable('services'), 404);

    $service = Service::where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    $relatedServices = Service::where('is_active', true)
        ->whereKeyNot($service->id)
        ->orderBy('sort_order')
        ->take(3)
        ->get();

    return view('website.service-show', compact('service', 'relatedServices'));
})->name('services.show');

Route::get('/lawyers', function () {
    return view('website.lawyers', [
        'lawyers' => Schema::hasTable('lawyers')
            ? Lawyer::where('is_active', true)->orderBy('sort_order')->get()
            : collect(),
    ]);
})->name('lawyers');

Route::get('/lawyers/{lawyer}', function (Lawyer $lawyer) {
    abort_unless($lawyer->is_active, 404);

    $relatedLawyers = Schema::hasTable('lawyers')
        ? Lawyer::where('is_active', true)
            ->whereKeyNot($lawyer->getKey())
            ->orderBy('sort_order')
            ->take(3)
            ->get()
        : collect();

    return view('website.lawyer-show', compact('lawyer', 'relatedLawyers'));
})->name('lawyers.show');

Route::get('/ticket', function () {
    return view('website.ticket', [
        'services' => Schema::hasTable('services')
            ? Service::where('is_active', true)->orderBy('sort_order')->get()
            : collect(),
    ]);
})->name('ticket');

Route::get('/faq', function () {
    return view('website.faq', [
        'faqs' => Schema::hasTable('faqs')
            ? Faq::where('is_active', true)->orderBy('sort_order')->get()
            : collect(),
    ]);
})->name('faq');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/legal-library', [LegalLibraryController::class, 'index'])->name('legal-library.index');
Route::get('/legal-library/search', [LegalLibraryController::class, 'search'])->name('legal-library.search');
Route::get('/legal-library/{category}', [LegalLibraryController::class, 'category'])
    ->whereIn('category', ['laws', 'judicial-decisions'])
    ->name('legal-library.category');
Route::get('/legal-library/{category}/{item}', [LegalLibraryController::class, 'show'])
    ->whereIn('category', ['laws', 'judicial-decisions'])
    ->name('legal-library.show');

Route::get('/training-courses/new', [TrainingCourseController::class, 'current'])->name('training-courses.current');
Route::get('/training-courses/previous', [TrainingCourseController::class, 'past'])->name('training-courses.past');
Route::get('/training-courses/{course}', [TrainingCourseController::class, 'show'])->name('training-courses.show');
Route::post('/training-courses/{course}/register', [TrainingCourseController::class, 'register'])->name('training-courses.register');

Route::post('/contact', [ContactRequestController::class, 'store'])->name('contact.store');
Route::post('/ticket', [ConsultationController::class, 'store'])->name('ticket.store');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');
    Route::patch('blog-posts/{post}/restore', [BlogPostController::class, 'restore'])->name('blog-posts.restore');
    Route::delete('blog-posts/{post}/force-delete', [BlogPostController::class, 'forceDelete'])->name('blog-posts.force-delete');
    Route::resource('blog-posts', BlogPostController::class)->except('show');
    Route::patch('blog-categories/{category}/restore', [BlogCategoryController::class, 'restore'])->name('blog-categories.restore');
    Route::delete('blog-categories/{category}/force-delete', [BlogCategoryController::class, 'forceDelete'])->name('blog-categories.force-delete');
    Route::resource('blog-categories', BlogCategoryController::class)->except('show');
    Route::patch('blog-tags/{tag}/restore', [BlogTagController::class, 'restore'])->name('blog-tags.restore');
    Route::delete('blog-tags/{tag}/force-delete', [BlogTagController::class, 'forceDelete'])->name('blog-tags.force-delete');
    Route::resource('blog-tags', BlogTagController::class)->except('show');
    Route::patch('clients/{client}/restore', [ClientController::class, 'restore'])->name('clients.restore');
    Route::delete('clients/{client}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.force-delete');
    Route::resource('clients', ClientController::class)->except('show');
    Route::patch('training-courses/{course}/restore', [AdminTrainingCourseController::class, 'restore'])->name('training-courses.restore');
    Route::delete('training-courses/{course}/force-delete', [AdminTrainingCourseController::class, 'forceDelete'])->name('training-courses.force-delete');
    Route::put('training-courses/{training_course}/registrations/{registration}', [AdminTrainingCourseController::class, 'updateRegistration'])->name('training-courses.registrations.update');
    Route::put('training-courses/{training_course}/registrations/{registration}/attendance', [AdminTrainingCourseController::class, 'updateAttendance'])->name('training-courses.registrations.attendance');
    Route::resource('training-courses', AdminTrainingCourseController::class);
    Route::patch('legal-library/{item}/restore', [LegalLibraryItemController::class, 'restore'])->name('legal-library.restore');
    Route::delete('legal-library/{item}/force-delete', [LegalLibraryItemController::class, 'forceDelete'])->name('legal-library.force-delete');
    Route::resource('legal-library', LegalLibraryItemController::class)
        ->parameters(['legal-library' => 'legalLibrary'])
        ->except('show');
    Route::patch('services/{service}/restore', [ServiceController::class, 'restore'])->name('services.restore');
    Route::delete('services/{service}/force-delete', [ServiceController::class, 'forceDelete'])->name('services.force-delete');
    Route::resource('services', ServiceController::class)->except('show');
    Route::patch('lawyers/{lawyer}/restore', [LawyerController::class, 'restore'])->name('lawyers.restore');
    Route::delete('lawyers/{lawyer}/force-delete', [LawyerController::class, 'forceDelete'])->name('lawyers.force-delete');
    Route::resource('lawyers', LawyerController::class)->except('show');
    Route::patch('faqs/{faq}/restore', [FaqController::class, 'restore'])->name('faqs.restore');
    Route::delete('faqs/{faq}/force-delete', [FaqController::class, 'forceDelete'])->name('faqs.force-delete');
    Route::resource('faqs', FaqController::class)->except('show');
    Route::patch('contact-requests/{contactRequest}/restore', [AdminContactRequestController::class, 'restore'])->name('contact-requests.restore');
    Route::delete('contact-requests/{contactRequest}/force-delete', [AdminContactRequestController::class, 'forceDelete'])->name('contact-requests.force-delete');
    Route::resource('contact-requests', AdminContactRequestController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::patch('consultations/{consultation}/restore', [AdminConsultationController::class, 'restore'])->name('consultations.restore');
    Route::delete('consultations/{consultation}/force-delete', [AdminConsultationController::class, 'forceDelete'])->name('consultations.force-delete');
    Route::resource('consultations', AdminConsultationController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::patch('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::resource('users', UserController::class)->except('show');
    Route::get('seo', [SeoSettingController::class, 'index'])->name('seo.index');
    Route::get('seo/{seo}/edit', [SeoSettingController::class, 'edit'])->name('seo.edit');
    Route::put('seo/{seo}', [SeoSettingController::class, 'update'])->name('seo.update');
});
