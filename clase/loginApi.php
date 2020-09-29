<?php
require_once __DIR__ . '/jSend.php';
require_once __DIR__ . '/registroApi.php';
class LoginApi
{
    const RECURSO_LOGIN = 'login';
    const DIR_USER_JSON = __DIR__ . '/../archivo/users.txt';
    
    public static function GenerarToken()
    {
        $jSend = new JSend('error');
        $email = $_POST['email'] ?? '';
        $clave = $_POST['password'] ?? '';
        $lista = Archivo::TraerTodosObjetosDeJson(self::DIR_USER_JSON);
        foreach ($lista as $value)
        {
            if($value->email === $email && $value->clave === sha1($clave))
            {
                $jwt = Token::CrearToken($value->email, $value->tipo);
                $jSend->status = 'success';
                $jSend->data->token = $jwt;
                return json_encode($jSend);
            }
        }
        $jSend->message = 'Email y/o clave incorrecto/s';
        return json_encode($jSend);
    }
}
?>