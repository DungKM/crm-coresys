<?php

namespace Webkul\Admin\Http\Controllers\SocialMessage;

use Webkul\Admin\Http\Controllers\Controller;   
use Illuminate\View\View;

class SocialMessageController extends Controller
{
    public function index(): View
    {
        return view('admin::social.index');
    }
}