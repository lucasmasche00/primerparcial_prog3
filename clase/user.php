<?php
class User
{
    public $email;
    public $clave;
    public $tipo;
    public $foto;

    public function __construct($email, $clave, $tipo, $foto)
    {
        $this->email = $email;
        $this->clave = sha1($clave);
        $this->tipo = $tipo;
        $this->foto = $foto;
    }

    public static function GetInstance($obj)
    {
        return new User($obj->email, $obj->clave, $obj->tipo, $obj->foto);
    }

    public static function ListStdToUser($lista)
    {
        $listaObj = array();
        foreach ($lista as $value)
        {
            array_push($listaObj, self::GetInstance($value));
        }
        return $listaObj;
    }
    
    public static function FindById($lista, $id)
    {
        foreach ($lista as $value)
        {
            if($value->email != null && $id != null && $value->email === $id)
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
        if(!self::IsInList($lista, $obj->email))
        {
            array_push($lista, $obj);
            return $lista;
        }
        return false;
    }

    public static function Remove($lista, $obj)
    {
        $obj = self::GetInstance($obj);
        if(self::IsInList($lista, $obj->email))
        {
            foreach ($lista as $key => $value)
            {
                if($value->email != null && $obj->email != null && $value->email === $obj->email)
                {
                    unset($lista[$key]);
                    return $lista;
                }
            }
        }
        return false;
    }
}
?>