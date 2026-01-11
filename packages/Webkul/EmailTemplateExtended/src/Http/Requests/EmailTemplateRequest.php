<?php

namespace Webkul\EmailTemplateExtended\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailTemplateRequest extends FormRequest
{
    // Xác thực yêu cầu người dùng 
    public function authorize(): bool
    {
        return true;
    }

    // Lấy các quy tắc áp dụng cho yêu cầu 
    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('email_templates', 'name')->ignore($id),
            ],
            'subject' => 'required|string|max:500',
            'content' => 'required|string',
            'category' => 'required|string|in:' . implode(',', array_keys($this->getCategories())),
            'locale' => 'required|string|max:10',
            'is_active' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'variables' => 'nullable|array',
            'variables.*.name' => 'required|string|max:100',
            'variables.*.type' => 'required|string|in:' . implode(',', array_keys($this->getVariableTypes())),
            'variables.*.default' => 'nullable',
            'variables.*.description' => 'nullable|string|max:255',
            'sample_data' => 'nullable|array',
            'metadata' => 'nullable|array',
            'preview_text' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|url|max:255',
            'cloned_from_id' => 'nullable|exists:email_templates,id',
        ];
    }

    // Thông báo cho các lỗi xác thực 
    public function messages(): array
    {
        return [
            'name.required' => trans('email_template_extended::app.validation.name-required'),
            'name.unique' => trans('email_template_extended::app.validation.name-unique'),
            'subject.required' => trans('email_template_extended::app.validation.subject-required'),
            'content.required' => trans('email_template_extended::app.validation.content-required'),
            'category.required' => trans('email_template_extended::app.validation.category-required'),
            'category.in' => trans('email_template_extended::app.validation.category-invalid'),
            'locale.required' => trans('email_template_extended::app.validation.locale-required'),
        ];
    }

    // Lấy các danh mục 
    protected function getCategories(): array
    {
        return [
            'sales' => 'Sales',
            'marketing' => 'Marketing',
            'support' => 'Support',
            'customer_care' => 'Customer Care',
            'workflow' => 'Workflow',
            'transactional' => 'Transactional',
            'notification' => 'Notification',
            'internal' => 'Internal',
            'billing' => 'Billing',
            'reporting' => 'Reporting',
            'general' => 'General',
        ];
    }

    // lấy kiểu dữ liệu biến 
    protected function getVariableTypes(): array
    {
        return [
            'text' => 'Text',
            'email' => 'Email',
            'number' => 'Number',
            'date' => 'Date',
            'datetime' => 'DateTime',
            'url' => 'URL',
            'phone' => 'Phone',
            'boolean' => 'Boolean',
        ];
    }

    //Chuẩn bị dữ liệu để kiểm tra đàu vào dữ liệu 
    protected function prepareForValidation(): void
    {
        // Convert comma-separated tags to array
        if ($this->has('tags') && is_string($this->tags)) {
            $this->merge([
                'tags' => array_map('trim', explode(',', $this->tags)),
            ]);
        }

        // Convert variables JSON string to array
        if ($this->has('variables') && is_string($this->variables)) {
            $this->merge([
                'variables' => json_decode($this->variables, true) ?? [],
            ]);
        }

        // Convert sample_data JSON string to array
        if ($this->has('sample_data') && is_string($this->sample_data)) {
            $this->merge([
                'sample_data' => json_decode($this->sample_data, true) ?? [],
            ]);
        }

        // Convert is_active to boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}