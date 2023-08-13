

<table>
    <thead>
    <tr>
        @foreach($headerArray as $key => $header)
            <th style="color: blueviolet">{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($studentsMarks as $key => $studentsMark)
        <tr>
            <td>{{$studentsMark->User->fname.' '.$studentsMark->User->lname}}</td>
            <td>{{$studentsMark->full_mark}}</td>

        </tr>
    @endforeach
    </tbody>
</table>





