@foreach ($posShortMenus as $sm)
    <li><a href="{{ route($sm->url) }}" title="{{$sm->name}}" class="head-tbl-icon" tabindex="-1"><span class="{{ $sm->icon }}" tabindex="-1"></span></a></li> 
@endforeach