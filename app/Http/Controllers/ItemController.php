<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests;  
use Illuminate\Http\Request;
use App\Models\User;

class ItemController extends Controller
{
    //
    public function pdf_download(Request $request)
    {
        $user = Auth::user();

        $dompdf = \App::make('dompdf.wrapper');

        $dompdf->loadHtml($request->pdf); 
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream("testTaskBackend", array("Attachment" => 1));
    }
}
