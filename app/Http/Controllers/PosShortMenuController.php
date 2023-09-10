<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PosShortMenuUser;
use Illuminate\Support\Facades\DB;

class PosShortMenuController extends Controller
{
    public function showModalForm()
    {
        $posShortMenus = DB::table('pos_short_menus')
            ->get();
        return view('sales.pos.ajax_view.short-menu-modal-form', compact('posShortMenus'));
    }

    public function show()
    {
        $posShortMenus = DB::table('pos_short_menu_users')
        ->where('user_id', auth()->user()->id)
        ->leftJoin('pos_short_menus', 'pos_short_menu_users.short_menu_id', 'pos_short_menus.id')
        ->select(
            'pos_short_menus.url',
            'pos_short_menus.name',
            'pos_short_menus.icon',
        )->orderBy('pos_short_menu_users.id', 'desc')
        ->get();
        return view('sales.pos.ajax_view.pos-shortcut-menus', compact('posShortMenus'));
    }

    public function editPageShow()
    {
        $posShortMenus = DB::table('pos_short_menu_users')
        ->where('user_id', auth()->user()->id)
        ->leftJoin('pos_short_menus', 'pos_short_menu_users.short_menu_id', 'pos_short_menus.id')
        ->select(
            'pos_short_menus.url',
            'pos_short_menus.name',
            'pos_short_menus.icon',
        )->orderBy('pos_short_menu_users.id', 'desc')
        ->get();
        return view('sales.pos.ajax_view.edipos-edit-shortcut-munus', compact('posShortMenus'));
    }

    public function store(Request $request)
    {
        $userShortMenus = PosShortMenuUser::where('user_id', auth()->user()->id)->get();
        foreach ($userShortMenus as $userShortMenu) {
            $userShortMenu->is_delete_in_update = 1;
            $userShortMenu->save();
        }

        if (isset($request->menu_ids)) {
            foreach ($request->menu_ids as $menu_id) {
                $shortMenuUser = PosShortMenuUser::where('short_menu_id', $menu_id)
                ->where('user_id', auth()->user()->id)
                ->first();

                if ($shortMenuUser) {
                    $shortMenuUser->is_delete_in_update = 0;
                    $shortMenuUser->save();
                }else {
                    $addShortMenuUser = new PosShortMenuUser();
                    $addShortMenuUser->short_menu_id = $menu_id;
                    $addShortMenuUser->user_id = auth()->user()->id;
                    $addShortMenuUser->save();
                }
            }
        }

        $unusedShortMenus = PosShortMenuUser::where('user_id', auth()->user()->id)->where('is_delete_in_update', 1)->get();
        foreach ($unusedShortMenus as $unusedShortMenu) {
            $unusedShortMenu->delete();
        }

        return response()->json('Successfully shortcut menu is added.');
    }
}
