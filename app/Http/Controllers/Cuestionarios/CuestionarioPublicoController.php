<?php

namespace App\Http\Controllers\Cuestionarios;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Cuestionarios\CuestionarioController;

class CuestionarioPublicoController extends Controller
{   
    
    /* ----------------------------- PUBLIC CUESTIONARIO -------------------------------------------------*/
    public function cuestionarioPublic($id)
    {        
        return view('cuestionarios.simple_encuesta');
    }

    public function getCuestionarioElems(Request $req)
    {
        $cuestionarioBase = new CuestionarioController();
        $id_cuestionario = $req->id ;
        $cuestionario = $cuestionarioBase->cuestionarioElementos($id_cuestionario);
        return response()->json([
            'data' => $cuestionario,
            'pruebareal' => $req->pruebareal,
        ]);
    }

    public function saveRespuestas(Request $req)
    {
        $questBase = new CuestionarioController();
        $objRes = $questBase->saveRespuestas($req);
        return response()->json(['data '=> $objRes]);
    }

}
