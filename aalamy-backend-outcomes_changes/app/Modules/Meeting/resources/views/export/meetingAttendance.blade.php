<table>
    <thead>
    <tr>
        @foreach($headerArray as $key => $header)
            <th style="color: blueviolet">{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($meeting->TargetUsers as $key => $targetUser)
        <tr>
            <td>{{$targetUser->Student->User->getFullName()}}</td>
            @if($targetUser->attendee_status)
                <td>present</td>
            @else
                <td>absent @if(isset($targetUser->note)) {{'/'.$targetUser->note}} @endif</td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>


