@foreach ($shortMenus as $sm)
    <div class="switch_bar">
        <a href="{{ route($sm->url) }}" class="bar-link">
            <span><i class="{{ $sm->icon }}"></i></span>
            <p>{{ $sm->name}}</p>
        </a>
    </div>
@endforeach
<div class="switch_bar">
    <a href="{{ route('short.menus.modal.form') }}" class="bar-link" id="addShortcutBtn">
        <span><i class="fas fa-plus-square text-success"></i></span>
        <p>@lang('Add Shortcut')</p>
    </a>
</div>
