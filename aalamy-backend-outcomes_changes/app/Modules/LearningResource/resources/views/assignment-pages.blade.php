<!DOCTYPE html>
<html>
<head>
    <title>Title From OnlineWebTutorBlog</title>
</head>
<body>
<div >

    @foreach($pages as $page)
        <img src="{{ $page }}" style="width: 708px;height: 880px;">
    @endforeach
    {{--<img src="{{ public_path('QuestionTypes-1643899839.jpeg') }}" style="width: 708px;height: 880px;">

    <img src="{{ public_path('QuestionTypes-1643899839.jpeg') }}" style="width: 708px;height: 880px;">--}}

    {{--
        <img src="{{ public_path('QuestionTypes-1643899839.jpeg') }}" style="width: 100px; height: 100px">
    --}}

</div>
{{--<h1>Title: {{ $title }}</h1>
<h3>Author: {{ $author }}</h3>
<p>ut aliquip ex ea commodoconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidata.</p>--}}
</body>
</html>
