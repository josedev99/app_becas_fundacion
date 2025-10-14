<?php


namespace App\Helpers;

use App\Models\TokenEmail;
use Google_Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GmailOAuthHelper
{
    public static function getValidAccessToken(string $email)
    {
        $token_email = DB::table('emails as e')
        ->join('token_emails as t', 't.email_id','e.id')
        ->select('e.id','e.id as token_id','e.tipo_email as protocol','e.cifrado','e.port','e.dominio','e.email','e.estado','t.access_token','t.refresh_token','t.json_config')
        ->where('e.email', $email)->first();

        if (!$token_email) {
            return null;
        }

        // Decodificar el json_config
        $client_secret = json_decode($token_email->json_config, true);
        if (!$client_secret) {
            return null; // Config inválida
        }

        $client = new Google_Client();
        $client->setAuthConfig($client_secret);
        $client->setAccessToken($token_email->access_token);

        // Refrescar token si está vencido
        if ($client->isAccessTokenExpired()) {
            $newToken = $client->fetchAccessTokenWithRefreshToken($token_email->refresh_token);

            if (!isset($newToken['access_token'])) {
                return null; // Falló la renovación
            }

            // Guardar nuevo access_token en la base de datos (usamos Eloquent aquí)
            TokenEmail::where('id', $token_email->token_id)->update([
                'access_token' => $newToken['access_token'],
                'expire'       => Carbon::now()->addSeconds($newToken['expires_in'] ?? 3600)->toDateTimeString(),
            ]);

            return (object)[
                "protocol" => $token_email->protocol,
                "host" => $token_email->dominio,
                "cifrado" => $token_email->cifrado,
                "port" => $token_email->port,
                "token" => $newToken['access_token']
            ];
        }
        return (object)[
            "protocol" => $token_email->protocol,
            "host" => $token_email->dominio,
            "cifrado" => $token_email->cifrado,
            "port" => $token_email->port,
            "token" => $token_email->access_token
        ];
    }

    /* public static function saveInitialToken(string $email, array $tokenData): GmailToken
    {
        return GmailToken::updateOrCreate(
            ['email' => $email],
            [
                'access_token'  => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'],
                'expires_at'    => Carbon::now()->addSeconds($tokenData['expires_in']),
            ]
        );
    } */
}