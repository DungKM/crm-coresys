<?php

namespace Webkul\Admin\Http\Controllers\Workflow;

use Webkul\Admin\Http\Controllers\Controller;
use Illuminate\View\View;

class WorkflowController extends Controller
{
    public function index(): View
    {
        return view('admin::workflow.index');
    }
public function dashboard()
{
    $datasets = [
        'labels' => ['MON 1','MON 2','MON 3','MON 4','MON 5','MON 6','MON 7'],
        'series' => [
            'facebook' => [10, 18, 12, 22, 15, 20, 26],
            'email'    => [2,  3,  1,  4,  2,  3,  1],
        ],
        'summary' => [
            'total_interactions' => 173,
            'fb_likes' => 128,
            'fb_comments' => 42,
            'email_replies' => 3,
        ],
        'channel_ratio' => [
            'facebook' => 68,
            'email' => 32,
        ],
    ];

    // Dành riêng cho <x-admin::charts.line>
    $chartLabels = $datasets['labels'];

    // Format datasets cho chart (phổ biến: name + values)
    $chartDatasets = [
        [
            'name'   => 'Facebook',
            'values' => $datasets['series']['facebook'] ?? [],
        ],
        [
            'name'   => 'Email',
            'values' => $datasets['series']['email'] ?? [],
        ],
    ];

    return view('admin::workflow.dashboard.index', compact('datasets', 'chartLabels', 'chartDatasets'));
}


    public function connectKey(): View
    {
        return view('admin::workflow.connectkey.index');
    }
}