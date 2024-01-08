<h1>
    The list of tasks
</h1>


<div>
    <!-- @if (count($tasks)) -->
    @forelse ($tasks as $task)
    <div>{{$task->title}}</div>
    @empty
    <div>There are no tasks!</div>
    @endforelse
    <!-- @endif -->
</div>
