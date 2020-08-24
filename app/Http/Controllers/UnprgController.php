<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Unprg;

class UnprgController extends Controller
{

    public function index(Request $request) 
    {
        switch($request->service)
        {
            case 'periods': return Unprg::periods();
            case 'teacherByCod': return Unprg::teacherByCod($request->cod);
            case 'studentByCod': return Unprg::studentByCod($request->cod);
            case 'coursesByCodTeacher': return Unprg::coursesByCodTeacher($request->cod);
            case 'coursesByCodTeacher': return Unprg::coursesByCodTeacher($request->cod);

            default: return [];
        }
    }


}
