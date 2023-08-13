

<table>
    <thead>
    <tr>
        @foreach($headerArray as $key => $header)
            <th style="color: blueviolet">{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($rosterStudents as $key => $rosterStudent)
        <tr>
            <td>{{$rosterStudent->ClassStudent->Student->User->fname.' '.$rosterStudent->ClassStudent->Student->User->lname}}</td>
            @foreach($rosterAssignments as $rosterAssignment)

                @if(isset($rosterAssignmentStudentsAttendances[$rosterStudent->ClassStudent->Student->id][$rosterAssignment->id][0]) && $rosterAssignmentStudentsAttendances[$rosterStudent->ClassStudent->Student->id][$rosterAssignment->id][0]->attendee_status)
                    <td>present</td>
                @else
{{--                    <td>absent</td>--}}
                    <td>absent @if(isset($rosterAssignmentStudentsAttendance->note)) {{'/'.$rosterAssignmentStudentsAttendance->note}} @endif</td>

                @endif

            @endforeach

{{--            <td>{{$rosterAssignmentStudentsAttendance->RosterAssignment->Roster->name}}</td>--}}
{{--            <td>{{$rosterAssignmentStudentsAttendance->RosterAssignment->Assignment->name}}</td>--}}
{{--            <td>{{$rosterAssignmentStudentsAttendance->RosterAssignment->start_date}}</td>--}}
        </tr>
    @endforeach
    </tbody>
</table>





