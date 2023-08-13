<table>
    <thead>
    <tr>
        @foreach($headerArray as $key => $header)
            <th style="color: blueviolet">{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($meeting as $key => $meeting)
        <tr>
            <td>{{$meeting->title}}</td>
            @if(isset($meeting->TargetUsers[0]))
                @if($meeting->TargetUsers[0]->attendee_status)
                    <td>present</td>
                @else
                    <td>absent @if(isset($meeting->TargetUsers[0]->note)) {{'/'.$meeting->TargetUsers[0]->note}} @endif</td>
                @endif
            @endif

        </tr>
    @endforeach
    </tbody>
</table>


