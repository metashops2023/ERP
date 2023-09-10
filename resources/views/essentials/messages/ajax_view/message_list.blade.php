@php
    use Carbon\Carbon;
@endphp

@foreach ($messages as $msg)
    <div class="message-box">
        <div class="user-block">
            @if ($msg->user_id == auth()->user()->id)
                <a href="{{ route('messages.delete', $msg->id) }}" class="float-end delete_message" id="delete">X</a>
            @endif
            <p class="user">{{ $msg->user_id == auth()->user()->id ? 'Me' : $msg->u_prefix.' '.$msg->u_name.' '.$msg->u_last_name }}</p>
            <p class="message-time"><i class="far fa-clock"></i> {{ Carbon::parse($msg->created_at)->diffForHumans() }}</p>
        </div>

        <div class="message-text">
            <p>{{ $msg->description }}</p>
        </div>
    </div>
@endforeach
