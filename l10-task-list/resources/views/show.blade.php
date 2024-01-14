@extends('layouts.app')

@section('title', $task->title)

@section('content')

<p>{{ $task->description }}</p>

@if($task->long_description)
    <p>{{ $task->long_description }}</p>
@endif

<p>Created at: {{ $task->created_at }}</p>
<p>Updated at: {{ $task->updated_at }}</p>

@if($task->completed)
    <p>This task is completed</p>
@else
    <p>This task is not completed</p>
@endif

@endsection
