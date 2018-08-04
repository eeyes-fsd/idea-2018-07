<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //页面控制示例
    public function root()
    {
        return view('pages.root');
    }
}
