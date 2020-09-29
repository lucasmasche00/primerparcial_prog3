<?php
class Auto
{
    public $patente;
    public $fecha_ingreso;
    public $email;
    public $fecha_egreso;
    public $importe;

    public function __construct($patente, $fecha_ingreso, $email, $fecha_egreso = '', $importe = 0)
    {
        $this->patente = $patente;
        $this->fecha_ingreso = $fecha_ingreso;
        $this->email = $email;
        $this->fecha_egreso = $fecha_egreso;
        $this->importe = $importe;
    }

    public static function GetInstance($obj)
    {
        return new Auto($obj->patente, $obj->fecha_ingreso, $obj->email, $obj->fecha_egreso, $obj->importe);
    }

    public static function ListStdToAuto($lista)
    {
        $listaObj = array();
        foreach ($lista as $value)
        {
            array_push($listaObj, self::GetInstance($value));
        }
        return $listaObj;
    }

    public function Equals($obj)
    {
        return ($this->patente != null && $obj->patente != null) ? ($this->patente === $obj->patente) : false;
    }
    
    public static function FindById($lista, $id)
    {
        foreach ($lista as $value)
        {
            if($value->patente != null && $id != null && $value->patente === $id)
                return $value;
        }
        return false;
    }

    public static function IsInList($lista, $id)
    {
        return (!is_null($lista) && count($lista) > 0) ? ((self::FindById($lista, $id) === false) ? false : true) : false;
    }

    public static function Add($lista, $obj)
    {
        if(!self::IsInList($lista, $obj->patente))
        {
            array_push($lista, $obj);
            return $lista;
        }
        return false;
    }

    public static function Remove($lista, $obj)
    {
        $obj = self::GetInstance($obj);
        if(self::IsInList($lista, $obj->patente))
        {
            foreach ($lista as $key => $value)
            {
                if($obj->Equals($value))
                {
                    unset($lista[$key]);
                    return $lista;
                }
            }
        }
        return false;
    }

    public static function CalcularImporte($fecha_ingreso, $fecha_egreso)
    {
        $importe = false;

        $fecha_ingreso_array = explode(' ', $fecha_ingreso);
        $fecha_ingreso_fecha = explode('-', $fecha_ingreso_array[0]);
        $fecha_ingreso_hora = explode(':', $fecha_ingreso_array[1]);
        
        $fecha_egreso_array = explode(' ', $fecha_egreso);
        $fecha_egreso_fecha = explode('-', $fecha_egreso_array[0]);
        $fecha_egreso_hora = explode(':', $fecha_egreso_array[1]);
        
        //fecha => 0 dia / 1 mes / 2 año
        //hora => 0 horas / 1 minutos / 2 segundos
        $segundos_ingreso = ($fecha_ingreso_fecha[2] * 365 * 24 * 60 * 60) + ($fecha_ingreso_fecha[1] * 30 * 24 * 60 * 60) + ($fecha_ingreso_fecha[0] * 24 * 60 * 60) + 
        ($fecha_ingreso_hora[0] * 60 * 60) + ($fecha_ingreso_hora[1] * 60) + $fecha_ingreso_hora[2];

        $segundos_egreso = ($fecha_egreso_fecha[2] * 365 * 24 * 60 * 60) + ($fecha_egreso_fecha[1] * 30 * 24 * 60 * 60) + ($fecha_egreso_fecha[0] * 24 * 60 * 60) + 
        ($fecha_egreso_hora[0] * 60 * 60) + ($fecha_egreso_hora[1] * 60) + $fecha_egreso_hora[2];

        $horas_estadia = (int)(($segundos_egreso - $segundos_ingreso) / 3600);
        if($horas_estadia < 1)
            $horas_estadia = 1;

        if($horas_estadia >= 12)//mas de 12 horas
        {
            $importe = $horas_estadia * 30;
        }
        else if($horas_estadia < 12 && $horas_estadia >= 4)//entre 4 y 12 horas
        {
            $importe = $horas_estadia * 60;
        }
        else//menos de 4 horas
        {
            $importe = $horas_estadia * 100;
        }

        return $importe;
    }

    
    public static function OrdenarPorFecha($autos)
    {
        function cmp($a, $b)
        {
            return strcmp($a->fecha_ingreso, $b->fecha_ingreso);
        }

        usort($autos, 'cmp');
        
        return $autos;
    }
}
?>