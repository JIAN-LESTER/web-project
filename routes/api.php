<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;

Route::middleware('auth:sanctum')->post('/chatbot/message', [ChatbotController::class, 'handleChat']);

?>
