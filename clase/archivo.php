<?php
class Archivo
{
    const DIR_TMP = __DIR__ . '/../tmp/';

    //=============================== JSON ===============================
    public static function TraerTodosObjetosDeJson($ruta)
    {
        if(file_exists($ruta))
        {
            $modo = 'r';
            $archivo = fopen($ruta, $modo);
            $lista = json_decode(fgets($archivo));
            fclose($archivo);
        }
        return $lista ?? array();
    }

    public static function GuardarObjetoJson($ruta, $objeto)
    {
        $lista = self::TraerTodosObjetosDeJson($ruta);
        $modo = 'w';
        $archivo = fopen($ruta, $modo);
        array_push($lista, $objeto);
        fwrite($archivo, json_encode($lista));
        fclose($archivo);
    }

    public static function GuardarListaJson($ruta, $lista)
    {
        $listaObj = Auto::ListStdToAuto($lista);
        $modo = 'w';
        $archivo = fopen($ruta, $modo);
        fwrite($archivo, json_encode($listaObj));
        fclose($archivo);
    }

    //=============================== IMAGENES ===============================
    public static function GuardarArchivo($ruta, $file)
    {
        if($file['size'] > 3584000 || !self::IsImage($file['type'])) //3.5MB
            return false;
        $aleatorio = rand(100000, 999999);
        $arrayName = explode(".", $file['name']);
        $extension = '.' . array_reverse($arrayName)[0];
        $nombreOriginal = $arrayName[0];

        $origen = $file['tmp_name'];
        $nombreNuevo = $nombreOriginal . '_' . $aleatorio . $extension;
        $destino = $ruta . $nombreNuevo;

        return move_uploaded_file($origen, $destino) ? $nombreNuevo : false;
    }

    public static function ModificarArchivo($ruta, $file, $oldFileName)
    {
        if(file_exists($ruta . $oldFileName) && !self::BorrarArchivo($ruta, $oldFileName))
            return false;
        if($file['size'] > 3584000 || !self::IsImage($file['type'])) //3.5MB
            return false;
        $aleatorio = rand(100000, 999999);
        $arrayName = explode(".", $file['name']);
        $extension = '.' . array_reverse($arrayName)[0];
        $nombreOriginal = $arrayName[0];

        $origen = $file['tmp_name'];
        $nombreNuevo = $nombreOriginal . '_' . $aleatorio . $extension;
        $destino = $ruta . $nombreNuevo;

        return move_uploaded_file($origen, $destino) ? $nombreNuevo : false;
    }

    public static function BorrarArchivo($ruta, $fileName)
    {
        return unlink($ruta . $fileName);
    }

    public static function RecuperarArchivo($ruta, $fileName)
    {
        return copy(self::DIR_TMP . $fileName, $ruta) ? unlink(self::DIR_TMP . $fileName) : false;
    }

    public static function PasarArchivoABackUp($ruta, $rutaBackup, $fileName)
    {
        return copy($ruta . $fileName, $rutaBackup . $fileName) ? unlink($ruta . $fileName) : false;
    }

    private static function IsImage($mimeType)
    {
        /*// images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml'*/
        switch ($mimeType)
        {
            case 'image/png':
            case 'image/jpeg':
            case 'image/gif':
            case 'image/bmp':
            case 'image/vnd.microsoft.icon':
            case 'image/tiff':
            case 'image/svg+xml':
                return true;
            default:
                return false;
        }
    }
}
?>