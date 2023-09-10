<form id="add_pos_shortcut_menu" action="{{ route('pos.short.menus.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="menu-list-area">
            <ul class="list-unstyled">
                @foreach ($posShortMenus as $sm)
                    @php
                        $smu = DB::table('pos_short_menu_users')
                        ->where('short_menu_id', $sm->id)
                        ->where('user_id', auth()->user()->id)->first(['user_id']);
                    @endphp
                    <li>
                        <p><input name="menu_ids[]" {{ $smu ? 'CHECKED' : '' }} type="checkbox" value="{{ $sm->id }}" id="check_menu">
                            <i class="{{ $sm->icon }} text-primary s-menu-icon ms-1"></i> <span class="s-menu-text">{{ $sm->name }}</span>
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</form>
