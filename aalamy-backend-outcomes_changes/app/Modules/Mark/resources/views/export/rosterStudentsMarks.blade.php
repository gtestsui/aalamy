

<table>
    <thead>
    <tr>
        @foreach($headerArray as $key => $header)
            <th style="color: blueviolet">{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($students as $key => $student)
        <tr>
            <td>{{$student->User->getFullName()}}</td>
            @foreach($rosterAssignments as $rosterAssignment)

                @if(isset($student->marks[$rosterAssignment->id]['full_mark']))
                    <td>{{$student->marks[$rosterAssignment->id]['full_mark']}}</td>
                @else
                    <td>0</td>
                @endif

            @endforeach

        </tr>
    @endforeach
    </tbody>
</table>





