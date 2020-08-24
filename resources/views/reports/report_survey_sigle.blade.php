@php
    $num_levels = count($data->scale->options);
@endphp

<table class="table table-sm table-bordered">
    <thead align="center">
        <tr>
            <td rowspan="6">{{__('N°') }}</td>
            <td rowspan="6">{{__('items') }}</td>
            <td>{{__('teacher') }}</td>
            <td colspan="{{ $data->teacher->courses->count()*$num_levels }}">{{ $data->teacher->fullname }}</td>
        </tr>

        <tr>
            <td>{{__('curso') }}</td>
            @foreach ($data->teacher->courses as $course)
            <td colspan="{{ $num_levels }}">{{ $course->name }} ({{ $course->group }})</td>
            @endforeach
        </tr>

        <tr>
            <td >{{__('N° enrolled students') }}</td>
            @foreach ($data->teacher->courses as $course)
            <td colspan="{{ $num_levels }}">{{ $course->enrolled_students }}</td>
            @endforeach
        </tr>

        <tr>
            <td >{{__('N° surveyed students') }}</td>
            @foreach ($data->teacher->courses as $course)
            <td colspan="{{ $num_levels }}">{{ $course->surveyed_students }}</td>
            @endforeach
        </tr>

        <tr>
            <td >{{__('% surveyed') }}</td>
            @foreach ($data->teacher->courses as $course)
            <td colspan="{{ $num_levels }}">{{ ($course->surveyed_students/$course->enrolled_students)*100 }}%</td>
            @endforeach
        </tr>

        <tr>
            <td>{{__('scale') }}</td>
            @foreach ($data->teacher->courses as $course)
                @foreach ($data->scale->options as $option)
                <td width="10">{{ $option->value }}</td>
                @endforeach
            @endforeach
        </tr>
    </thead>
    

    <tbody>
        @foreach ($data->items as $item)

            <tr>
                <td class="text-center">{{ $loop->index+1 }}</td>
                <td colspan="2">{{ $item->name }}</td>
                @foreach ($item->courses as $course)
                    @foreach ($course->scale->options as $option)
                        <td class="text-center" width="50">{{ $option->check? 'X': '' }}</td>
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr align="center">
            <td colspan="3" rowspan="2">Total</td>
            @foreach ($data->teacher->courses as $course)
            <td colspan="{{ $num_levels }}">{{ number_format($course->avg, 5) }}</td>
            @endforeach
        </tr>
        <tr align="center">
            @foreach ($data->teacher->courses as $course)
            <td colspan="{{ $num_levels }}">{{ $course->level }}</td>
            @endforeach
        </tr>
    </tfoot>

</table>