<table>
    <thead>
    <tr>
        @foreach($headerArray as $key => $header)
            <th style="color: blueviolet">{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($rosterAssignmentStudentsAttendances as $key => $rosterAssignmentStudentsAttendance)
        <tr>
            <td>{{$rosterAssignmentStudentsAttendance->Student->User->fname.' '.$rosterAssignmentStudentsAttendance->Student->User->lname}}</td>
            @if($rosterAssignmentStudentsAttendance->attendee_status)
                <td>present</td>
            @else
                <td>absent @if(isset($rosterAssignmentStudentsAttendance->note)) {{'/'.$rosterAssignmentStudentsAttendance->note}} @endif</td>
            @endif
            <td>{{$rosterAssignmentStudentsAttendance->RosterAssignment->Roster->name}}</td>
            <td>{{$rosterAssignmentStudentsAttendance->RosterAssignment->Assignment->name}}</td>
            <td>{{$rosterAssignmentStudentsAttendance->RosterAssignment->start_date}}</td>
        </tr>
    @endforeach
    </tbody>
</table>


