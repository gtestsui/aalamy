

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
            <td>{{$student->final_grade}}</td>
            @foreach($rosterAssignments as $rosterAssignment)

                @if(isset($student->roster_assignments_marks[$rosterAssignment->id]['final_mark']))
                    <td>{{$student->roster_assignments_marks[$rosterAssignment->id]['final_mark']}}</td>
                @else
                    <td>0</td>
                @endif

            @endforeach

            @foreach($quizzes as $quiz)

                @if(isset($student->quizzes_marks[$quiz->id]['final_mark']))
                    <td>{{$student->quizzes_marks[$quiz->id]['final_mark']}}</td>
                @else
                    <td>0</td>
                @endif

            @endforeach

            @if($thereAnExternalMarks)
                <td>{{$student->external_mark}}</td>
            @endif


        </tr>
    @endforeach
    </tbody>
</table>





