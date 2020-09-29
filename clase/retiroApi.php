<?php
require_once __DIR__ . '/auto.php';
require_once __DIR__ . '/jSend.php';
class RetiroApi
{
    const RECURSO_RETIRO = 'retiro';
    const DIR_AUTO_JSON = __DIR__ . '/../archivo/autos.txt';
    
    public static function Modificar($parametro)
    {
        $jSend = new JSend('error');
        $patente = $parametro ?? 0;
        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        //try
        //{
            $usuarioLogeado = Token::DecodificarToken($token);
            if($patente !== '')
            {
                date_default_timezone_set('America/Argentina/Buenos_Aires');
                $fecha_egreso = date('d-m-Y H:i:s');
                
                $lista = Archivo::TraerTodosObjetosDeJson(self::DIR_AUTO_JSON);
                
                if(Auto::IsInList($lista, $patente))
                {
                    $oldAuto = Auto::FindById($lista, $patente);
                    if($oldAuto->fecha_egreso === '')
                    {
                        $importe = Auto::CalcularImporte($oldAuto->fecha_ingreso, $fecha_egreso);
                        if($importe !== false)
                        {
                            $nuevoImporte = $oldAuto->importe + $importe;
                            $lista = Auto::Remove($lista, $oldAuto);
                            if($lista !== false)
                            {
                                $auto = new Auto($patente, $oldAuto->fecha_ingreso, $oldAuto->email, $fecha_egreso, $nuevoImporte);
                                
                                $lista = Auto::Add($lista, $auto);
                                if($lista !== false)
                                {
                                    Archivo::GuardarListaJson(self::DIR_AUTO_JSON, $lista);
                                    
                                    $jSend->status = 'success';
                                    $jSend->data->mensajeExito = 'Modificacion exitosa';
                                }
                                else
                                {
                                    $jSend->message = 'Error al modificar';
                                }
                            }
                            else
                            {
                                $jSend->message = 'Error al modificar';
                            }
                        }
                        else
                        {
                            $jSend->message = 'Error al calcular importe';
                        }
                    }
                    else
                    {
                        $jSend->message = 'Auto no estacionado';
                    }
                }
                else
                {
                    $jSend->message = 'Patente no encontrada';
                }
            }
            else
            {
                $jSend->message = 'Patente valida requerida';
            }
        /*}
        catch (\Throwable $th)
        {
            $jSend->message = 'Token invalido';
        }*/
        return json_encode($jSend);
    }
}
?>