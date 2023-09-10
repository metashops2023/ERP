<?php

namespace App\Http\Controllers;

use App\Models\ShortMenuUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShortMenuController extends Controller
{
    public function showModalForm()
    {
        $shortMenus = DB::table('short_menus')
            ->get();
        return view('dashboard.ajax_view.short-menu-modal-form', compact('shortMenus'));
    }

    public function show()
    {
        $shortMenus = DB::table('short_menu_users')
        ->where('user_id', auth()->user()->id)
        ->leftJoin('short_menus', 'short_menu_users.short_menu_id', 'short_menus.id')
        ->select(
            'short_menus.url',
            'short_menus.name',
            'short_menus.icon',
        )->orderBy('short_menu_users.id', 'desc')
        ->get();
        return view('dashboard.ajax_view.switch_bar_cards', compact('shortMenus'));
    }

    public function store(Request $request)
    {
        $userShortMenus = ShortMenuUser::where('user_id', auth()->user()->id)->get();
        foreach ($userShortMenus as $userShortMenu) {
            $userShortMenu->is_delete_in_update = 1;
            $userShortMenu->save();
        }

        if (isset($request->menu_ids)) {
            foreach ($request->menu_ids as $menu_id) {
                $shortMenuUser = ShortMenuUser::where('short_menu_id', $menu_id)
                ->where('user_id', auth()->user()->id)
                ->first();

                if ($shortMenuUser) {
                    $shortMenuUser->is_delete_in_update = 0;
                    $shortMenuUser->save();
                }else {
                    $addShortMenuUser = new ShortMenuUser();
                    $addShortMenuUser->short_menu_id = $menu_id;
                    $addShortMenuUser->user_id = auth()->user()->id;
                    $addShortMenuUser->save();
                }
            }
        }

        $unusedShortMenus = ShortMenuUser::where('user_id', auth()->user()->id)->where('is_delete_in_update', 1)->get();
        foreach ($unusedShortMenus as $unusedShortMenu) {
            $unusedShortMenu->delete();
        }

        return response()->json('Successfully shortcut menu is added.');
    }
}
