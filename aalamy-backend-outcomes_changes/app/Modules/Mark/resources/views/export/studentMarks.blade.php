

<table>
    <thead>
    <tr>
        @foreach($headerArray as $key => $header)
            <th style="color: blueviolet">{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$student->User->fname.' '.$student->User->lname}}</td>
        @foreach($rosterAssignmentsWithMarks as $rosterAssignmentsMark)
            <td>{{$rosterAssignmentsMark->full_mark}}</td>

        @endforeach

    </tr>
    </tbody>
</table>





