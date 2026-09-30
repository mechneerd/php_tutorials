<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/learn')->name('dashboard');

    Route::livewire('learn', 'pages::learn-dashboard')->name('learn.index');
    Route::livewire('roadmap', 'pages::roadmap')->name('roadmap');
    Route::livewire('lessons/{lesson:slug}', 'pages::lesson-show')->name('lessons.show');
    Route::livewire('playground', 'pages::code-playground')->name('playground');
    Route::livewire('mentor', 'pages::ai-mentor')->name('mentor');
    Route::livewire('interviews', 'pages::interview-practice')->name('interviews');
    Route::livewire('projects', 'pages::projects-index')->name('projects');

    Route::livewire('admin/content', 'pages::admin-content')
        ->middleware('can:admin')
        ->name('admin.content');

    Route::prefix('senior')->name('senior.')->group(function () {
        Route::livewire('/', 'pages::senior-dashboard')->name('index');
        Route::livewire('design', 'pages::senior-design')->name('design');
        Route::livewire('design/{designCase:slug}', 'pages::senior-design-case')->name('design.show');
        Route::livewire('incidents', 'pages::senior-incidents')->name('incidents');
        Route::livewire('incidents/{incident:slug}', 'pages::senior-incident-show')->name('incidents.show');
        Route::livewire('reviews', 'pages::senior-code-review')->name('reviews');
        Route::livewire('reviews/{codeReviewDrill:slug}', 'pages::senior-review-show')->name('reviews.show');
        Route::livewire('internals', 'pages::senior-internals')->name('internals');
        Route::livewire('internals/{internalsTopic:slug}', 'pages::senior-internal-show')->name('internals.show');
        Route::livewire('star', 'pages::senior-star')->name('star');
        Route::livewire('simulation', 'pages::senior-simulation')->name('simulation');
        Route::livewire('simulation/{interviewSimulation:slug}', 'pages::senior-simulation-show')->name('simulation.show');
    });
});

require __DIR__.'/settings.php';
