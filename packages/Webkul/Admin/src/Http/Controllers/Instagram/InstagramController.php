<?php

namespace Webkul\Admin\Http\Controllers\Instagram;

use Webkul\Admin\Http\Controllers\Controller;
use Illuminate\View\View;

class InstagramController extends Controller
{
    public function index(): View
    {
        return view('admin::instagram.index');
    }
}