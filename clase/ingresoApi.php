<?php
require_once __DIR__ . '/auto.php';
require_once __DIR__ . '/jSend.php';
class IngresoApi
{
    const RECURSO_INGRESO = 'ingreso';
    const DIR_AUTO_JSON = __DIR__ . '/../archivo/autos.txt';
    
    public static function Alta()
    {
        $jSend = new JSend('error');
        $patente = $_POST['patente'] ?? 0;
        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        try
        {
            $usuarioLogeado = Token::DecodificarToken($token);
            if($patente !== '')
            {
                $email = $usuarioLogeado->email ?? '';
                if($email !== '')
                {
                    date_default_timezone_set('America/Argentina/Buenos_Aires');
                    $fecha_ingreso = date('d-m-Y H:i:s');
                    
                    $lista = Archivo::TraerTodosObjetosDeJson(self::DIR_AUTO_JSON);
    
                    if(!Auto::IsInList($lista, $patente))
                    {
                        $auto = new Auto($patente, $fecha_ingreso, $email);
                        
                        Archivo::GuardarObjetoJson(self::DIR_AUTO_JSON, $auto);
                        
                        $jSend->status = 'success';
                        $jSend->data->mensajeExito = 'Guardado exitoso';
                    }
                    else
                    {
                        $jSend->message = 'Patente repetido';
                    }
                }
                else
                {
                    $jSend->message = 'Error con el token';
                }
            }
            else
            {
                $jSend->message = 'Patente valido requerido';
            }
        }
        catch (\Throwable $th)
        {
            $jSend->message = 'Token invalido';
        }
        return json_encode($jSend);
    }
    
    public static function ListarTodo()
    {
        $jSend = new JSend('error');
        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        try
        {
            $usuarioLogeado = Token::DecodificarToken($token);

            $autos = Archivo::TraerTodosObjetosDeJson(self::DIR_AUTO_JSON);
            $jSend->data->autos = Auto::OrdenarPorFecha($autos);
            
            $jSend->status = 'success';
        }
        catch (\Throwable $th)
        {
            $jSend->message = 'Token invalido';
        }
        return json_encode($jSend);
    }
}
?>