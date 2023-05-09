@extends('layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col col-md-4">
            <nav class="panel panel-default">
                <div class="panel-heading">フォルダ</div>
                <div class="panel-body">
                    <a href="{{ route('folders.create') }}" class="btn btn-default btn-block">
                        フォルダを追加する
                    </a>
                </div>
                <div class="list-group">
                    @foreach($folders as $folder)
                    <a href="{{ route('tasks.index', ['id' => $folder->id]) }}" class="list-group-item {{ $current_folder_id === $folder->id ? 'active' : '' }}">
                        {{ $folder->title }}
                    </a>
                    @endforeach
                </div>
            </nav>
        </div>
        <div class="column col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">タスク</div>
                <div class="panel-body">
                    <div class="text-right">
                        <a href="{{ route('tasks.create', ['id' => $current_folder_id]) }}" class="btn btn-default btn-block">
                            タスクを追加する
                        </a>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>タイトル</th>
                            <th>状態</th>
                            <th>期限</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>
                                @if($task->status_class == '未着手')
                                <span class="label label-danger">{{ $task->status_class }}</span>
                                @elseif($task->status_class == '着手中')
                                <span class="label label-info">{{ $task->status_class }}</span>
                                @else
                                <span class="label">{{ $task->status_class }}</span>
                                @endif
                            </td>
                            <td>{{ $task->formatted_due_date }}</td>
                            <td><a href="{{ route('tasks.edit', ['id' => $task->folder_id, 'task_id' => $task->id]) }}">
                                    編集
                                </a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection