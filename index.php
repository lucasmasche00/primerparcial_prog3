<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/clase/token.php';
require_once __DIR__ . '/clase/jSend.php';
require_once __DIR__ . '/clase/archivo.php';
require_once __DIR__ . '/clase/registroApi.php';
require_once __DIR__ . '/clase/loginApi.php';
require_once __DIR__ . '/clase/ingresoApi.php';
require_once __DIR__ . '/clase/retiroApi.php';

$path = $_SERVER['PATH_INFO'] ?? '';
$method = $_SERVER['REQUEST_METHOD'] ?? '';
$recurso = explode('/', $path)[1] ?? '';
$parametro = explode('/', $path)[2] ?? '';
$jSend = new JSend('error');

switch ($recurso)
{
    case RegistroApi::RECURSO_REGISTRO:
        switch ($method)
        {
            case 'POST':
                if($parametro === '')
                {
                    $respuesta = RegistroApi::Alta();
                    $stdJSend = json_decode($respuesta);
                    switch ($stdJSend->status)
                    {
                        case 'success':
                            $jSend->status = 'success';
                            $jSend->data->mensajeExito = $stdJSend->data->mensajeExito;
                            break;
                        case 'error':
                            $jSend->message = $stdJSend->message;
                            break;
                        default:
                            $jSend->message = 'Error de envio';
                            break;
                    }
                }
                else
                {
                    $jSend->message = 'Direccion erronea';
                }
                break;
            default:
                $jSend->message = 'Metodo HTTP no valido';
                break;
        }
        break;
    case LoginApi::RECURSO_LOGIN:
        switch ($method) {
            case 'POST':
                if($parametro === '')
                {
                    $respuesta = LoginApi::GenerarToken();
                    $stdJSend = json_decode($respuesta);
                    switch ($stdJSend->status)
                    {
                        case 'success':
                            $jSend->status = 'success';
                            $jSend->data->token = $stdJSend->data->token;
                            break;
                        case 'error':
                            $jSend->message = $stdJSend->message;
                            break;
                        default:
                            $jSend->message = 'Error de envio';
                            break;
                    }
                }
                else
                {
                    $jSend->message = 'Direccion erronea';
                }
                break;
            default:
                $jSend->message = 'Metodo HTTP no valido';
                break;
        }
        break;
    case IngresoApi::RECURSO_INGRESO:
        switch ($method) {
            case 'GET':
                if($parametro === '')
                {
                    $respuesta = IngresoApi::ListarTodo();
                    $stdJSend = json_decode($respuesta);
                    switch ($stdJSend->status)
                    {
                        case 'success':
                            $jSend->status = 'success';
                            $jSend->data->autos = $stdJSend->data->autos;
                            break;
                        case 'error':
                            $jSend->message = $stdJSend->message;
                            break;
                        default:
                            $jSend->message = 'Error de envio';
                            break;
                    }
                }
                else
                {
                    $jSend->message = 'Direccion erronea';
                }
                break;
            case 'POST':
                if($parametro === '')
                {
                    $respuesta = IngresoApi::Alta();
                    $stdJSend = json_decode($respuesta);
                    switch ($stdJSend->status)
                    {
                        case 'success':
                            $jSend->status = 'success';
                            $jSend->data->mensajeExito = $stdJSend->data->mensajeExito;
                            break;
                        case 'error':
                            $jSend->message = $stdJSend->message;
                            break;
                        default:
                            $jSend->message = 'Error de envio';
                            break;
                    }
                }
                else
                {
                    $jSend->message = 'Direccion erronea';
                }
                break;
            default:
                $jSend->message = 'Metodo HTTP no valido';
                break;
        }
        break;
    case RetiroApi::RECURSO_RETIRO:
        switch ($method) {
            case 'GET':
                if($parametro === '')
                {
                    $jSend->message = 'Direccion erronea';
                }
                else
                {
                    $respuesta = RetiroApi::Modificar($parametro);
                    $stdJSend = json_decode($respuesta);
                    switch ($stdJSend->status)
                    {
                        case 'success':
                            $jSend->status = 'success';
                            $jSend->data->mensajeExito = $stdJSend->data->mensajeExito;
                            break;
                        case 'error':
                            $jSend->message = $stdJSend->message;
                            break;
                        default:
                            $jSend->message = 'Error de envio';
                            break;
                    }
                }
                break;
            default:
                $jSend->message = 'Metodo HTTP no valido';
                break;
        }
        break;
    default:
        $jSend->message = 'Direccion erronea';
        break;
}
echo json_encode($jSend);
?>