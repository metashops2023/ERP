<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ReleaseNoteController extends Controller
{
    public function index()
    {
        return view('settings.version_release_note.index');
    }
}
