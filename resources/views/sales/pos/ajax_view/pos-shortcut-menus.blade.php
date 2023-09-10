@foreach ($posShortMenus as $sm)
    <li>
        <a href="{{ route($sm->url) }}" title="{{$sm->name}}" class="head-tbl-icon" tabindex="-1"><span class="{{ $sm->icon }}" ></span></a>
    </li>
@endforeach

<li>
    <a href="{{ route('pos.short.menus.modal.form') }}" id="addPosShortcutBtn" class="head-tbl-icon border-none" tabindex="-1"><span class="fas fa-plus"></span></a>
</li>


