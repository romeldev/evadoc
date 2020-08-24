<table style="width:100%">
    <tr align="center">
        <td colspan="9"><b>UNIVERSIDAD NACIONAL PEDRO RUIZ GALLO</b></td>
    </tr>
    <tr align="center">
        <td colspan="9"><b>VICERECTORADO ACADEMICO</b></td>
    </tr>
    <tr align="center">
        <td colspan="9" style="background-color: #d6d8db;"><b>EVALUACION DE DESEMPENO DOCENTE POR INDICADORES</b></td>
    </tr>
</table>
<br>
<table style="width:100%">
    <tr>
        <td colspan="4"><b>FACULTAD</b></td>
        <td><b>faulty_name</b></td>
    </tr>
    <tr>
        <td colspan="4"><b>PROGRAMA ACADEMICO</b></td>
        <td><b>{{$evaluation->user->school_name}}</b></td>
    </tr>
    <tr>
        <td colspan="4"><b>SEMESTRE ACADEMICO</b></td>
        <td><b>{{$evaluation->period_text}}</b></td>
    </tr>
    <tr>
        <td colspan="4"><b>Nro. DOCENTES EVALUADOS</b></td>
        <td align="center"><b>{{count($evaluation->teachers)}}</b></td>
    </tr>
    <tr>
        <td colspan="4"><b>Nro. DOCENTES EVALUADOS QUE PARTICIPARON EN EL SEMESTRE ACADEMICO</b></td>
        <td align="center"><b>0</b></td>
    </tr>
</table>
<br>
<table style="width:100%" border="1">
    <thead>
        <tr >
            <td rowspan="3" style="vertical-align:middle" align="center"><b>Nro</b></td>
            <td rowspan="3" style="vertical-align:middle" align="center"><b>APELLIDOS Y NOMBRES</b></td>
            <td colspan="{{count($evaluation->indicators)}}" align="center"><b>INDICADORES</b></td>
            <td rowspan="3" style="vertical-align:middle" align="center"><b>TOTAL</b></td>
            <td rowspan="3" style="vertical-align:middle" align="center"><b>NIVEL</b></td>
        </tr>
        <tr style="width:100%">
            @foreach ($evaluation->indicators as $indicator)
            <td align="center" width="50"><b>{{$indicator->name}}</b></td>
            @endforeach
        </tr>
        <tr style="width:100%">
            @foreach ($evaluation->indicators as $indicator)
            <td align="center" width="50"><b>P({{$indicator->weight}})</b></td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($evaluation->teachers as $key => $teacher)
                <tr style="width:100%">
                    <td rowspan="{{count($teacher->courses)+1}}" style="vertical-align:middle" align="center">{{$key+1}}</td>
                    <td align="left" style="background-color: #c6e0f5;"><b>{{ strtoupper($teacher->fullname) }}</b></td>
                    @foreach ($evaluation->indicators as $indicator)
                    <td align="center" style="background-color: #c6e0f5;"><b>
                        {{ number_format(isset($teacher->indicators[$indicator->id])?$teacher->indicators[$indicator->id]->avg:0, 1) }}
                    </b></td>
                    @endforeach
                    <td align="center" style="background-color: #c6e0f5;"><b>{{ number_format($teacher->total, 1) }}</b></td>
                    <td align="center" style="background-color: #c6e0f5;"><b>{{ $teacher->level }}</b></td>
                </tr style="width:100%">

                @foreach ($teacher->courses as $course)
                    <tr>
                        <td align="right">{{$course->name}} ({{$course->group}})</td>
                        @foreach ($evaluation->indicators as $indicator)
                        <td align="center" width="50">
                            {{ number_format(isset($course->indicators[$indicator->id])?$course->indicators[$indicator->id]->value:0, 1) }}
                        </td>
                        @endforeach
                        <td align="center">{{ number_format($course->total, 1) }}</td>
                        <td align="center">{{ $course->level }}</td>
                    </tr>
                @endforeach
                
        @endforeach
    </tbody>
</table>

