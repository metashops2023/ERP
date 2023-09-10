<div class="row">
    <div class="col-md-4">
        <p><b>@lang('Todo ID') : </b> {{ $todo->todo_id }}</p>
        <p><b>@lang('Entry Date') : </b>{{ date('d/m/Y', strtotime($todo->created_at)) }}</p>
        <p><b>@lang('Task') : </b> {{ $todo->task }}</p>

    </div>

    <div class="col-md-4">
        <p><b>@lang('Due Date') : </b>{{ date('d/m/Y', strtotime($todo->due_date)) }}</p>
        <p><b>@lang('Status') : </b> {{ $todo->status }}</p>
        <p><b>@lang('Priority') : </b> {{ $todo->priority }}</p>
    </div>

    <div class="col-md-4">
        <p><b>@lang('Assigned By') : </b> {{ $todo->admin ? $todo->admin->prefix.' '.$todo->admin->name.' '.$todo->admin->last_name : 'N/A'}}</p>
        <p><b>@lang('Assigned To') : </b>
            @foreach ($todo->todo_users as $todo_user)
                {{ $todo_user->user->prefix.' '.$todo_user->user->name.' '.$todo_user->user->last_name }},
            @endforeach
        </p>
    </div>
    <hr class="mt-1">
</div>

<div class="row">
    <p><b>@lang('Description') :</b> </p>
    <p>{{ $todo->description }}</p>
</div>
