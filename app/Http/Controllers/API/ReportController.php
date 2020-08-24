<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Report;
use App\Model\Evaluation;
use App\Model\Survey;
use App\Model\Reply;
use App\Model\RepoUnprg;

class ReportController extends Controller
{

    public function meta()
    {
        $data['evaluations'] = Evaluation::all();
        $data['schools'] = RepoUnprg::vTreeSchools();
        return $data;
    }

    public function report(Request $request )
    {
        // $shool_code = $request->user()->school_code;
        // $request['school_code'] = 24;
        // $shool_code = 24;

        $request->validate([
            'school_code' => 'required',
            'evaluation_id' => 'required|integer|gt:0',
            'report_type' => 'required|integer',
        ]);

        if( $request->report_type == Report::TYPE_SURVEY_SINGLE )
        {
            $request->validate(['teacher_code' => 'required']);
        }

        

        // R1
        if( $request->report_type == Report::TYPE_INDICATORS )
        {
            $evaluation = Report::reportIndicators( $request->evaluation_id, $request->school_code );
            $evaluation->user = $request->user();
            return view('reports.report_indicators', compact('evaluation') );
        }

        // R2
        if( $request->report_type == Report::TYPE_SURVEY )
        {
            $data = Report::reportSurvey( $request->evaluation_id, $request->school_code );

            return [
                'view' => view('reports.report_survey', compact('data') )->render(),
                'datachart' => $data->datachart,
            ];
        }

        // R3
        if( $request->report_type == Report::TYPE_SURVEY_AVG )
        {
            $data = Report::reportSurveyAvg( $request->evaluation_id, $request->school_code );
            return view('reports.report_survey_avg', compact('data') );
        }

        // R4
        if( $request->report_type == Report::TYPE_SURVEY_SINGLE )
        {
            // dd( $request->evaluation_id, $shool_code, $request->teacher_code );
            $data = Report::reportSurveySingle( $request->evaluation_id, $request->school_code, $request->teacher_code );
            return view('reports.report_survey_sigle', compact('data') );
        }

        


        return null;
    }

    public function generate(Request $request)
    {
        header("Content-Type: application/vnd.ms-excel; charset=utf8mb4");
        header("Content-Disposition: attachment; filename=report_survey.xls");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        echo $request->content;
    }

    public function general_download(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|integer|gt:0',
            'extension' => 'required',
            'filename' => 'required',
        ]);

        $data = new \Stdclass;
        $data->extension = in_array($request->extension, ['pdf', 'xls'])? $request->extension: null;
        $data->filename = $request->filename? $request->filename: 'FILE_'.\Carbon\Carbon::now()->timestamp;
        $data->content = $this->general($request)->render();

        return view('export.index', compact('data'));
    }
}
