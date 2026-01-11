<?php

namespace Webkul\EmailTemplateExtended\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\EmailTemplateExtended\Models\EmailTemplate;

class EmailTemplateDataGrid extends DataGrid
{
    protected $primaryColumn = 'id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('email_templates')
            ->select(
                'id',
                'name',
                'subject',
                'category',
                'locale',
                'is_active',
                'usage_count',
                'tags',
                'created_at'
            )
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => 'Id',
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => 'Tên mẫu',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'subject',
            'label'      => 'Tiêu đề',
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => false,
            'closure'    => function ($row) {
                // Giới hạn độ dài và loại bỏ các biến
                $subject = strip_tags($row->subject);
                $subject = preg_replace('/\{%.*?%\}/', '[biến]', $subject);
                return \Illuminate\Support\Str::limit($subject, 50);
            },
        ]);

        $this->addColumn([
            'index'      => 'category',
            'label'      => 'Danh mục',
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                $categories = EmailTemplate::getCategories();
                return $categories[$row->category] ?? $row->category;
            },
        ]);

        $this->addColumn([
            'index'      => 'locale',
            'label'      => 'Ngôn ngữ',
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                $locales = [
                    'vi' => 'Tiếng Việt',
                    'en' => 'English',
                ];
                return $locales[$row->locale] ?? $row->locale;
            },
        ]);

        $this->addColumn([
            'index'      => 'is_active',
            'label'      => 'Trạng thái',
            'type'       => 'boolean',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                if ($row->is_active) {
                    return '<span class="badge badge-md badge-success">Hoạt động</span>';
                }
                return '<span class="badge badge-md badge-danger">Tạm dừng</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'usage_count',
            'label'      => 'Lượt dùng',
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Ngày tạo',
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'index'  => 'view',
            'icon'   => 'icon-eye',
            'title'  => 'Xem chi tiết',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.email_templates.show', $row->id);
            },
        ]);

        $this->addAction([
            'index'  => 'edit',
            'icon'   => 'icon-edit',
            'title'  => 'Chỉnh sửa',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.email_templates.edit', $row->id);
            },
        ]);

        $this->addAction([
            'index'  => 'delete',
            'icon'   => 'icon-delete',
            'title'  => 'Xóa',
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.email_templates.destroy', $row->id);
            },
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'icon'   => 'icon-delete',
            'title'  => 'Xóa hàng loạt',
            'method' => 'POST',
            'url'    => route('admin.email_templates.mass_delete'),
        ]);
    }
}