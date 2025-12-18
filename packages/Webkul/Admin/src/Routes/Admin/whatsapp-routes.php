<?php

use App\Http\Controllers\WhatsAppController as AppWhatsAppController;

/**
 * Whatsapp routes.
 */

// Routes handled by the controller in the main App directory
// Route without lead ID - redirects to latest conversation
Route::get('leads/chat', [AppWhatsAppController::class, 'chatLatest'])->name('admin.leads.chat');
// Route with specific lead ID
Route::get('leads/{id}/chat', [AppWhatsAppController::class, 'chat'])->name('admin.leads.chat.index');
Route::post('leads/{id}/chat/send', [AppWhatsAppController::class, 'sendFromChat'])->name('admin.leads.chat.send');
Route::get('leads/whatsapp/{id}', [AppWhatsAppController::class, 'sendToLead'])->name('admin.whatsapp.send');

// Route for AJAX reply from chat page
Route::post('leads/{id}/whatsapp-reply', [AppWhatsAppController::class, 'reply'])->name('admin.leads.whatsapp.reply');
Route::get('leads/{id}/whatsapp-new-messages', [AppWhatsAppController::class, 'getNewMessages'])->name('admin.leads.whatsapp.new-messages');

// Message action routes
Route::post('whatsapp/message/{id}/pin', [AppWhatsAppController::class, 'togglePin'])->name('admin.whatsapp.message.pin');
Route::post('whatsapp/message/{id}/star', [AppWhatsAppController::class, 'toggleStar'])->name('admin.whatsapp.message.star');
Route::delete('whatsapp/message/{id}', [AppWhatsAppController::class, 'deleteMessage'])->name('admin.whatsapp.message.delete');
Route::get('whatsapp/message/{id}/info', [AppWhatsAppController::class, 'getMessageInfo'])->name('admin.whatsapp.message.info');
Route::post('whatsapp/message/{id}/forward', [AppWhatsAppController::class, 'forwardMessage'])->name('admin.whatsapp.message.forward');
