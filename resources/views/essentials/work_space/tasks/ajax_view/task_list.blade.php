@if (count($ws_tasks) > 0)
    @foreach ($ws_tasks as $task)
        <tr class="bg-white">
            <td class="text-start text-muted task_index">{{ $loop->index + 1 }}.</td>

            <td class="text-start task_details">
                <div class="task_area" data-id="{{ $task->id }}">
                    <span id="task_name" class="text-muted"> {{ $task->task_name }} </span>
                    <a href="{{ route('workspace.task.delete', $task->id) }}" class="text-danger float-end" title="Delete" id="delete"><i class="far fa-trash-alt ms-1"></i></a>
                    <a href="#" class="text-muted" title="Edit" id="edit_task_btn"><i class="fas fa-pencil-alt"></i></a>
                </div>

                <div class="input-group">
                    {{-- <input type="text" name="edit_task_name" class="form-control form-control-sm d-none edit_task_name" id="edit_task_name" value="{{ $task->task_name }}"> --}}
                    <textarea  name="edit_task_name" class="form-control form-control-sm d-none edit_task_name" id="edit_task_name" cols="10" rows="2">{{ $task->task_name }}</textarea>
                    <div class="input-group-prepend add_button update_task_button">
                        <span class="input-group-text edit_task_name custom-modify d-none"><i class="far fa-check-circle text-success"></i></span>
                    </div>
                </div>
            </td>

            <td class="text-start tast_status">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-sm {{ $task->u_id ? 'btn-primary' : 'btn-warning' }} rounded" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-user {{ $task->u_id ? 'text-white' : 'text-dark' }}"></i> <b class="{{ $task->u_id ? 'text-white' : 'text-dark' }}">{{ $task->u_id ? $task->u_prefix.' '.$task->u_name.' '.$task->u_last_name : 'Not-Assigned' }}</b>
                    </button>

                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item mt-1" href="{{ route('workspace.task.assign.user', $task->id) }}" id="assign_user" data-user_id=""><i class="fas fa-user text-primary"></i> @lang('None')</a>
                        @foreach ($ws_users as $ws_user)
                            <a class="dropdown-item mt-1 text-muted" href="{{ route('workspace.task.assign.user', $task->id) }}" id="assign_user" data-user_id="{{ $ws_user->id }}"><i class="fas fa-user text-primary"></i> {{ $ws_user->prefix.' '.$ws_user->name.' '.$ws_user->last_name }}</a>
                        @endforeach
                    </div>
                </div>
            </td>

            <td class="text-start">
                <div class="btn-group" role="group">
                    @php
                        $btn_class = '';
                        if($task->priority == 'High'){
                            $btn_class = 'btn-danger';
                        }elseif ($task->priority == 'Low') {
                            $btn_class = 'btn-warning';
                        }elseif ($task->priority == 'Medium') {
                            $btn_class = 'btn-secondary';
                        }else {
                            $btn_class = 'btn-1';
                        }
                    @endphp
                    <button title="Priority" id="btnGroupDrop1" type="button" class="btn btn-sm {{ $btn_class }} rounded" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <b>{{ $task->priority }}</b>
                    </button>

                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item mt-1" href="{{ route('workspace.task.priority', $task->id) }}" data-priority="High" id="change_priority"><i class="fas fa-circle text-danger"></i> <b>@lang('High Priority')</b></a>
                        <a class="dropdown-item mt-1" href="{{ route('workspace.task.priority', $task->id) }}" data-priority="Low" id="change_priority"><i class="fas fa-circle text-warning"></i> <b>@lang('Low Priority')</b></a>
                        <a class="dropdown-item mt-1" href="{{ route('workspace.task.priority', $task->id) }}" data-priority="Medium" id="change_priority"><i class="fas fa-circle text-secondary"></i> <b>@lang('Medium Priority')</b></a>
                        <a class="dropdown-item mt-1" href="{{ route('workspace.task.priority', $task->id) }}" data-priority="Urgent" id="change_priority"><i class="fas fa-circle text-1"></i> <b>@lang('Urgent Priority')</b></a>
                    </div>
                </div>
            </td>

            <td class="text-start">
                <div class="btn-group" role="group">
                    @php
                        $class = "";
                        if($task->status == 'In-Progress'){
                            $class = "btn-secondary";
                        }elseif($task->status == 'Pending') {
                            $class = "btn-danger";
                        }else {
                            $class = "btn-info";
                        }
                    @endphp
                    <button id="btnGroupDrop1" type="button" class="btn btn-sm {{ $class }}  text-white rounded" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <b>{{ $task->status }}</b>
                    </button>

                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item mt-1" href="{{ route('workspace.task.status', $task->id) }}" data-status="Pending" id="change_status"><i class="fas fa-circle text-danger"></i> <b>@lang('Pending')</b></a>
                        <a class="dropdown-item mt-1" href="{{ route('workspace.task.status', $task->id) }}" data-status="In-Progress" id="change_status"><i class="fas fa-circle text-secondary"></i> <b>@lang('In-Progress')</b></a>
                        <a class="dropdown-item mt-1" href="{{ route('workspace.task.status', $task->id) }}" data-status="Complated" id="change_status"><i class="fas fa-circle text-info"></i> <b>@lang('Completed')</b></a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <th colspan="3">@lang('No-Task-Available')</th>
    </tr>
@endif

