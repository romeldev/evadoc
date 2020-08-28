<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use DB;
use App\Model\Unprg;
use App\Model\Menu;
use App\Model\Survey;
use App\Model\Evaluation;
use App\Model\Scale;
use App\Model\Level;
use App\Model\Indicator;
use App\Http\Resources\SurveyResource;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        switch( $request->resource )
        {

            case 'menu': return Menu::tree();
            case 'all-roles-and-schools': return $this->allRolesAndSchools();
            case 'all-permissions': return $this->allPermissions();
            case 'all-periods': return $this->allPeriods();
            case 'all-surveys': return $this->allSurveys();

            case 'faculty-all': return $this->facultyAll();

            case 'meta-crud-evaluation': return $this->metaCrudEvaluation();

            case 'meta-crud-survey': return $this->metaCrudSurvey();

            default: [];
        }
    }

    private function metaCrudSurvey()
    {
        $data['scales'] = Scale::select('id', 'name')->get();
        $data['levels'] = Level::select('id', 'name')->get();

        return $data;
    }

    private function metaCrudEvaluation()
    {
        $data['periods'] = $this->allPeriods();
        $data['surveys'] = $this->allSurveys();
        $data['levels'] = Level::select('id', 'name')->get();
        $data['indicatorTypes'] = $this->allIndicatorTypes();
        $data['status'] = Evaluation::arrayStatus();
        return $data;
    }

    private function facultyAll()
    {
        $faculties = Unprg::facultyAll();

        foreach($faculties as $faculty)
        {
            $faculty->schools = Unprg::schoolsByFacultyCode($faculty->code);
        }
        return $faculties;
    }

    private function allIndicatorTypes()
    {
        foreach(Indicator::arrayTypes() as $id => $name )
        {
            $data[] = [
                'id' => $id,
                'name' => $name,
            ];
        }
        return $data;
    }

    private function allScales()
    {
        return Scale::select('id', 'type', 'name')->get();
    }

    private function allSurveys()
    {
        return SurveyResource::collection(Survey::all());
    }

    private function allPeriods()
    {
        return Unprg::periods();
    }

    private function allRolesAndSchools()
    {
        $roles = DB::table('roles')->get();
        $schools = Unprg::schoolAll();
        
        $data['roles'] = $roles;
        $data['schools'] = $schools;
        return $data;
    }

    private function allPermissions()
    {
        $data = DB::table('permissions')->get();
        
        return $data;
    }

}
