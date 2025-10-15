<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Envoyer l'email
            Mail::raw("Message de: {$request->name}\nEmail: {$request->email}\n\nMessage:\n{$request->message}", function ($message) use ($request) {
                $message->to(config('mail.from.address'))
                       ->subject('Nouveau message de contact - MAKERS')
                       ->from($request->email, $request->name);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Votre message a été envoyé avec succès!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors de l\'envoi du message.'
            ], 500);
        }
    }
}