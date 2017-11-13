<table id="{!! $id !!}" class="{!! $class !!}">
    <colgroup>
        @for ($i = 0; $i < count($columns); $i++)
        <col class="con{!! $i !!}" />
        @endfor
    </colgroup>
    <thead class="thead-default">
    <tr>
        @foreach($columns as $i => $c)
        <th class="text-center">{!! $c !!}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
    <tr>
        @foreach($d as $dd)
        <td>{!! $dd !!}</td>
        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>