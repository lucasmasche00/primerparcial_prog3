<?php
use \Firebase\JWT\JWT;
class Token
{
    private static $llave = 'primerparcial';

    public static function CrearToken($email, $tipo)
    {
        $key = self::$llave;
        $payload = array(
            "email" => $email,
            "tipo" => $tipo
        );
        
        return JWT::encode($payload, $key);
    }

    public static function DecodificarToken($jwt)
    {
        return JWT::decode($jwt, self::$llave, array('HS256'));
    }
}
?>