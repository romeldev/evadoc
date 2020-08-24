@php
    // dd($data);
@endphp

<table class="table table-sm table-bordered">

    <tr>
        <td colspan="4">{{__('number of teachers evaluated')}}: {{ $data->number_teachers_evaluated}}</td>
    </tr>

    <tr>
        <td colspan="4">{{__('Total teachers')}}: {{ $data->total_teachers}}</td>
    </tr>

    <tr>
        <td>{{__('N°')}}</td>
        <td>{{__('fullname')}}</td>
        <td>{{__('score')}}</td>
        <td>{{__('level')}}</td>
    </tr>
    @foreach ($data->teachers as $teacher)
        @php
            $showData = [
                'school_code' => $data->school->code,
                'evaluation_id' => $data->evaluation->id,
                'teacher_code' => $teacher->code,
            ];
        @endphp
        <tr>
            <td>{{ $loop->index }}</td>
            <td>
                {{-- <a href="#" onclick="showsSurveySingle({{$data->school->code}}, {{$data->evaluation->id}}, {{$teacher->code}})" title="ver encuesta">{{ $teacher->fullname }}</a> --}}

                <input type="hidden" value="{{ json_encode($showData) }}">
                <a href="#" class="btn-show-survey-single" style="text-decoration:none;">
                    {{ $teacher->fullname }}
                </a>
            </td>
            <td>{{ number_format($teacher->score, 1) }}</td>
            <td>{{ $teacher->score_level }}</td>
        </tr>
    @endforeach
</table>