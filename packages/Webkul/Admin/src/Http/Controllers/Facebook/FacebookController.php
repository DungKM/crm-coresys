<?php

namespace Webkul\Admin\Http\Controllers\Facebook;

use Webkul\Admin\Http\Controllers\Controller;
use Illuminate\View\View;

class FacebookController extends Controller
{
    public function index(): View
    {
        return view('admin::facebook.index');
    }
}