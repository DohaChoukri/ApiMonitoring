<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class ConnexionController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email'    => 'required|email|exists:users,email',
                'password' => 'required|string|min:8',
            ]);

            if (!Auth::attempt($validated)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Les identifiants sont incorrects.',
                ], 401);
            }

            $user = Auth::user();

            $user = $request->user();
            $token = $user->createToken('auth_token');

            return response()->json([
                'status'      => true,
                'message'     => 'Connexion réussie.',
                'user'        => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                ],
                'role'=> $user->getRoleNames(),
                'userPermissions'=> $user->getAllPermissions(),
                'access_token' => $token->plainTextToken,
                'token_type'   => 'Bearer',
                'expires_in'   => config('sanctum.expiration', null),
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Erreur de validation.',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Déconnexion réussie.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Erreur lors de la déconnexion.',
            ], 500);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            $user = $request->user();
            $newToken = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'       => true,
                'message'      => 'Token rafraîchi avec succès.',
                'access_token' => $newToken,
                'token_type'   => 'Bearer',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Impossible de rafraîchir le token.',
            ], 401);
        }
    }

    public function forgotPassword(Request $request)
    {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $token = Str::random(60);

            // Enregistrer ou mettre à jour le token dans la table password_reset_tokens
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]
            );

            // Envoyer l'email avec le lien
            $resetUrl = url("/reset-password/{$token}?email={$request->email}");

            Mail::raw("Cliquez ici pour réinitialiser votre mot de passe: $resetUrl", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Réinitialisation du mot de passe');
            });

            return response()->json([
                'status'  => true,
                'message' => 'Un email de réinitialisation a été envoyé si l’adresse existe.',
            ], 200);
    }

    // public function resetPassword(Request $request)
    // {
    //     $request->validate([
    //         'email'    => 'required|email|exists:users,email',
    //         'token'    => 'required|string',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $record = DB::table('password_reset_tokens')
    //                 ->where('email', $request->email)
    //                 ->where('token', $request->token)
    //                 ->first();

    //     if (!$record) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Token invalide ou expiré.',
    //         ], 400);
    //     }

    //     // Mettre à jour le mot de passe de l'utilisateur
    //     $user = User::where('email', $request->email)->first();
    //     $user->password = bcrypt($request->password);
    //     $user->save();

    //     // Supprimer le token
    //     DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Mot de passe réinitialisé avec succès.',
    //     ], 200);
    // }
}
