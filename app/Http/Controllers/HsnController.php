<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hsn;

class HsnController extends Controller
{
    public function index()
    {
        $hsns = Hsn::all();
        return response()->json([
            'status' => 'success',
            'hsns' => $hsns
        ]);
    }
}
