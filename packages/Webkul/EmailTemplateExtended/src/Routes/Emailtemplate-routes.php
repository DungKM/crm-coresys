<?php

use Illuminate\Support\Facades\Route;
use Webkul\EmailTemplateExtended\Http\Controllers\EmailTemplateController;

/**
 * Email Template Extended Routes
 */
Route::group([
    'middleware' => ['web', 'user'],
    'prefix'     => 'admin/email-templates',
], function () {
    
    // Standard CRUD routes
    Route::get('/', [EmailTemplateController::class, 'index'])
        ->name('admin.email_templates.index');
    
    Route::get('/create', [EmailTemplateController::class, 'create'])
        ->name('admin.email_templates.create');
    
    Route::post('/store', [EmailTemplateController::class, 'store'])
        ->name('admin.email_templates.store');

    Route::post('/mass-delete', [EmailTemplateController::class, 'massDelete'])
        ->name('admin.email_templates.mass_delete');

    // Route dùng riêng cho create template 
    Route::post('/preview-draft', [EmailTemplateController::class, 'previewDraft'])
        ->name('admin.email_templates.preview_draft');
    
    Route::post('/import', [EmailTemplateController::class, 'import'])
        ->name('admin.email_templates.import');
        
    Route::get('/{id}', [EmailTemplateController::class, 'show'])
        ->name('admin.email_templates.show');
    
    Route::get('/{id}/edit', [EmailTemplateController::class, 'edit'])
        ->name('admin.email_templates.edit');
    
    Route::put('/{id}', [EmailTemplateController::class, 'update'])
        ->name('admin.email_templates.update');
    
    Route::delete('/{id}', [EmailTemplateController::class, 'destroy'])
        ->name('admin.email_templates.destroy');
    
    // Extended features routes
    Route::match(['get', 'post'], '/{id}/preview', [EmailTemplateController::class, 'preview'])
        ->name('admin.email_templates.preview');
    
    Route::post('/{id}/clone', [EmailTemplateController::class, 'clone'])
        ->name('admin.email_templates.clone');
    
    Route::post('/{id}/toggle-active', [EmailTemplateController::class, 'toggleActive'])
        ->name('admin.email_templates.toggle_active');
    
    Route::post('/{id}/increment-usage', [EmailTemplateController::class, 'incrementUsage'])
        ->name('admin.email_templates.increment_usage');
    
    Route::get('/{id}/analyze-variables', [EmailTemplateController::class, 'analyzeVariables'])
        ->name('admin.email_templates.analyze_variables');
    
    // Export routes - Show export options page
    Route::get('/{id}/export', [EmailTemplateController::class, 'export'])
        ->name('admin.email_templates.export');
    
    // Export specific formats
    Route::get('/{id}/export-html', [EmailTemplateController::class, 'exportHtml'])
        ->name('admin.email_templates.export_html');
        
    Route::get('/{id}/export-json', [EmailTemplateController::class, 'exportJson'])
        ->name('admin.email_templates.export_json');
        
    Route::get('/{id}/export-zip', [EmailTemplateController::class, 'exportZip'])
        ->name('admin.email_templates.export_zip');
});

/**
 * API Routes 
 */
Route::group([
    'middleware' => ['web', 'user'],
    'prefix'     => 'api/admin/email-templates',
], function () {
    
    Route::get('/tags', function () {
        $repository = app('Webkul\EmailTemplateExtended\Repositories\EmailTemplateRepository');
        return response()->json($repository->getAllTags());
    })->name('api.admin.email_templates.tags');
    
    Route::get('/categories', function () {
        $model = app('Webkul\EmailTemplateExtended\Contracts\EmailTemplate');
        return response()->json($model::getCategories());
    })->name('api.admin.email_templates.categories');
    
    Route::get('/statistics', function () {
        $repository = app('Webkul\EmailTemplateExtended\Repositories\EmailTemplateRepository');
        return response()->json($repository->getStatistics());
    })->name('api.admin.email_templates.statistics');
});