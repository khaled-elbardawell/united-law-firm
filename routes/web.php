<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('website.index');
})->name('home');


Route::get('/about', function () {
    return view('website.about');
})->name('about');

Route::get('/contact', function () {
    return view('website.contact');
})->name('contact');

Route::get('/faq', function () {
    return view('website.faq');
})->name('faq');

Route::get('/services', function () {
    return view('website.services');
})->name('services');

Route::get('/lawyers', function () {
    return view('website.lawyers');
})->name('lawyers');

Route::get('/ticket', function () {
    return view('website.ticket');
})->name('ticket');