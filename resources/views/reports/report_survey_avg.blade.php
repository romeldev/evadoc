<style>
    .text-bold {
        font-weight: bold;
    }
</style>

{{-- <table border="1">
    <tr>
        <td colspan="2">{{ __('institutional score') }}:</td>
        <td>{{ number_format($data->institutional_score,1)}} {{$data->institutional_score_level }}</td>
    </tr>
    <tr>
        <td colspan="2">{{ __('N° teachers evaluated') }}:</td>
        <td>{{ $data->number_teachers_evaluated}}</td>
    </tr>
</table>
<br> --}}


<table style="width:100%" border="0">
    <tr class="text-bold" style="text-align:center"><td colspan="5"> {{__('UNIVERSIDAD NACIONAL PEDRO RUIZ GALLO')}} </td></tr>
    <tr class="text-bold" style="text-align:center"><td colspan="5"> {{__('VICERECTORADO ACADEMICO')}} </td></tr>
    <tr class="text-bold" style="text-align:center"><td colspan="5"> {{__('RESULTADOS PROMEDIO DEL DESEMPEÑO DOCENTE SEGUN OPINION DEL ESTUDIANTE')}} </td></tr>

    <tr><td colspan="5"><br></td></tr>

    <tr class="text-bold">
        <td colspan="1" width="50"></td>
        <td colspan="3">{{__('FACULTADA DE INGENIERIA CIVIL DE SISTEMAS Y ARQUITECTURA')}}</td>
        <td colspan="1" width="100"></td>
    </tr>

    <tr class="text-bold">
        <td colspan="1"></td>
        <td colspan="3">{{__('PROGRAMA ACADEMICO DE INGENIERIA DE SISTEMAS')}}</td>
        <td colspan="1"></td>
    </tr>

    <tr class="text-bold">
        <td colspan="1"></td>
        <td colspan="3">{{__('SEMESTRE ACADEMICO 2019-II')}}</td>
        <td colspan="1"></td>
    </tr>

    <tr class="text-bold">
        <td colspan="1"></td>
        <td colspan="3">{{ __('institutional score') }}: {{ number_format($data->institutional_score,1)}} {{$data->institutional_score_level }}</td>
        <td colspan="1"></td>
    </tr>

    <tr class="text-bold">
        <td colspan="1"></td>
        <td colspan="3">{{ __('N° teachers evaluated') }}: {{ $data->number_teachers_evaluated}}</td>
        <td colspan="1"></td>
    </tr>

    <tr class="text-bold">
        <td colspan="1"></td>
        <td colspan="3"><br></td>
        <td colspan="1"></td>
    </tr>

    <tr class="text-bold">
        <td colspan="1"></td>
        <td colspan="3">{{__('criterios evaluados')}}</td>
        <td colspan="1"></td>
    </tr>

    @foreach ($data->items as $item)
        <tr class="text-bold">
            {{-- <td>{{$item->id}}</td> --}}
            <td colspan="1"></td>
            <td colspan="1" style="text-align:center" width="50">{{$loop->index+1}}</td>
            <td colspan="2">{{$item->name}}</td>
            <td colspan="1"></td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="2">
                <table border="1">
                    <tr class="text-bold">
                        <td width="200">{{ __('criterios de desempeño') }}</td>
                        <td width="150">{{ __('nro de docentes') }}</td>
                    </tr>

                    @foreach ($item->intervals as $interval)
                        <tr style="text-align:center;">
                            <td>{{ $interval->value }}</td>
                            <td>{{ $interval->num_teachers }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
            <td colspan="1"></td>
        </tr>
        <tr>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="2"><br></td>
            <td colspan="1"></td>
        </tr>
    @endforeach
</table>


{{-- <table class="table table-sm table-bordered">

    <tbody>
        @foreach ($data->items as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td colspan="2">{{$item->name}}</td>
               
            </tr>

            <tr>
                <td></td>
                <td>{{ __('criterios de desempeño') }}</td>
                <td>{{ __('nro de docentes') }}</td>
            </tr>

            @foreach ($item->intervals as $interval)
                <tr>
                    <td></td>
                    <td>{{ $interval->value }}</td>
                    <td>{{ $interval->num_teachers }}</td>
                </tr>
            @endforeach

            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>



</table> --}}