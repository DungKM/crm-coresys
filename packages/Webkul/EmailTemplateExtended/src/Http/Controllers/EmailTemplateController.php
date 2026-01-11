<?php

namespace Webkul\EmailTemplateExtended\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\EmailTemplateExtended\Repositories\EmailTemplateRepository;
use Webkul\EmailTemplateExtended\DataGrids\EmailTemplateDataGrid;

class EmailTemplateController extends Controller
{
    public function __construct(
        protected EmailTemplateRepository $emailTemplateRepository
    ) {}

    /**
     * Danh sách email template
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(EmailTemplateDataGrid::class)->toJson();
        }
        
        $categories = \Webkul\EmailTemplateExtended\Models\EmailTemplate::getCategories();
        $allTags = $this->emailTemplateRepository->getAllTags();
        
        return view('email_template_extended::index', compact('categories', 'allTags'));
    }

    /**
     * Tạo mới email mẫu
     */
    public function create()
    {
        $categories = \Webkul\EmailTemplateExtended\Models\EmailTemplate::getCategories();
        $variableTypes = \Webkul\EmailTemplateExtended\Models\EmailTemplate::getVariableTypes();
        
        return view('email_template_extended::create', compact('categories', 'variableTypes'));
    }

    /**
     * Lưu nội dung vừa tạo
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'         => 'required|string|max:255|unique:email_templates,name',
            'subject'      => 'required|string|max:500',
            'content'      => 'required|string',
            'category'     => 'required|string',
            'locale'       => 'required|string|max:10',
            'editor_mode'  => 'required|in:classic,pro',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->guard('user')->id();

        // Xử lý builder_config nếu là Pro mode
        if ($request->editor_mode === 'pro' && $request->has('builder_config')) {
            $builderConfig = is_string($request->builder_config) 
                ? $request->builder_config 
                : json_encode($request->builder_config);
            $data['builder_config'] = $builderConfig;
            
            // Lưu generated HTML vào content
            if ($request->has('generated_html') && !empty($request->generated_html)) {
                $data['content'] = $request->generated_html;
            }
        }

        // Xử lý variables
        if ($request->has('variables')) {
            $data['variables'] = $this->processVariables($request->input('variables'));
        }

        // Xử lý tags
        if ($request->has('tags')) {
            $data['tags'] = is_string($request->tags) 
                ? array_map('trim', explode(',', $request->tags))
                : $request->tags;
        }

        Event::dispatch('email_template.create.before');
        
        $emailTemplate = $this->emailTemplateRepository->create($data);
        
        Event::dispatch('email_template.create.after', $emailTemplate);

        session()->flash('success', trans('email_template_extended::app.templates.messages.create-success'));
        
        return redirect()->route('admin.email_templates.index');
    }

    /**
     * Hiển thị nội dung chi tiết
     */
    public function show(int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
        
        // Chỉ extract content cho mục đích hiển thị
        if ($emailTemplate->editor_mode === 'pro' && $emailTemplate->content) {
            $emailTemplate->content = $this->extractEmailContent($emailTemplate->content);
        }
        
        $clones = $emailTemplate->clones()->get();
        $clonedFrom = $emailTemplate->clonedFrom;
        
        return view('email_template_extended::show', compact('emailTemplate', 'clones', 'clonedFrom'));
    }

    /**
     * Chỉnh sửa nội dung email mẫu
     */
    public function edit(int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
        $categories = \Webkul\EmailTemplateExtended\Models\EmailTemplate::getCategories();
        $variableTypes = \Webkul\EmailTemplateExtended\Models\EmailTemplate::getVariableTypes();
        
        Log::info('=== EDIT TEMPLATE ===', [
            'id' => $id,
            'name' => $emailTemplate->name,
            'editor_mode' => $emailTemplate->editor_mode,
            'has_builder_config' => !empty($emailTemplate->builder_config),
            'builder_config_length' => strlen($emailTemplate->builder_config ?? ''),
            'builder_config_preview' => substr($emailTemplate->builder_config ?? '', 0, 100),
        ]);
        
        return view('email_template_extended::edit', compact('emailTemplate', 'categories', 'variableTypes'));
    }

    /**
     * Cập nhật nội dung đã chỉnh sửa
     */
    public function update(Request $request, int $id)
    {
        // Validation rules based on editor mode
        $rules = [
            'name'         => 'required|string|max:255|unique:email_templates,name,' . $id,
            'subject'      => 'required|string|max:500',
            'category'     => 'required|string',
            'locale'       => 'required|string|max:10',
            'editor_mode'  => 'required|in:classic,pro',
        ];
        
        // Classic mode requires content
        if ($request->input('editor_mode') !== 'pro') {
            $rules['content'] = 'required|string';
        }
        
        $this->validate($request, $rules);

        // Get old template for fallback
        $oldTemplate = $this->emailTemplateRepository->find($id);
        if (!$oldTemplate) {
            session()->flash('error', 'Template không tồn tại');
            return back();
        }

        $data = $request->only(['name', 'subject', 'preview_text', 'category', 'locale', 'is_active']);
        
        // Process based on editor mode
        if ($request->editor_mode === 'pro') {
            
            // Process builder config
            if ($request->has('builder_config') && !empty(trim($request->builder_config))) {
                $builderConfigRaw = $request->builder_config;
                
                if (in_array($builderConfigRaw, ['undefined', 'null', ''])) {
                    $data['builder_config'] = null;
                } else {
                    if (is_string($builderConfigRaw)) {
                        $decoded = json_decode($builderConfigRaw, true);
                        
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $data['builder_config'] = $builderConfigRaw;
                        } else {
                            $data['builder_config'] = null;
                            session()->flash('error', 'Builder config JSON không hợp lệ: ' . json_last_error_msg());
                            return back()->withInput();
                        }
                    } else {
                        $data['builder_config'] = json_encode($builderConfigRaw);
                    }
                }
            } else {
                // Keep old builder_config if no new one provided
                $data['builder_config'] = $oldTemplate->builder_config;
            }
            
            // Process HTML content with priority order
            $htmlContent = null;
            
            // Priority 1: generated_html
            if ($request->has('generated_html')) {
                $generatedHtml = trim($request->generated_html);
                
                if (!empty($generatedHtml)) {
                    $textContent = strip_tags($generatedHtml);
                    
                    if (strlen(trim($textContent)) > 0) {
                        $htmlContent = $generatedHtml;
                    }
                }
            }
            
            // Priority 2: content field
            if (!$htmlContent && $request->has('content')) {
                $contentField = trim($request->content);
                
                if (!empty($contentField)) {
                    $textContent = strip_tags($contentField);
                    
                    if (strlen(trim($textContent)) > 0) {
                        $htmlContent = $contentField;
                    }
                }
            }
            
            // Priority 3: Keep old content
            if (!$htmlContent) {
                $htmlContent = $oldTemplate->content;
            }
            
            // Final validation
            if (empty(trim($htmlContent))) {
                session()->flash('error', 'Nội dung HTML không được để trống');
                return back()->withInput();
            }
            
            $finalTextContent = strip_tags($htmlContent);
            if (strlen(trim($finalTextContent)) === 0) {
                session()->flash('error', 'Nội dung HTML không hợp lệ hoặc chỉ chứa tags rỗng');
                return back()->withInput();
            }
            
            $data['content'] = $htmlContent;
            
        } else {
            // Classic mode
            $data['builder_config'] = null;
            
            if ($request->has('content') && !empty(trim($request->content))) {
                $data['content'] = $request->content;
            }
        }
        
        // Process variables
        if ($request->has('variables')) {
            $data['variables'] = $this->processVariables($request->input('variables'));
        }
        
        // Process tags
        if ($request->has('tags')) {
            if (is_string($request->tags)) {
                $data['tags'] = array_filter(array_map('trim', explode(',', $request->tags)));
            } else {
                $data['tags'] = $request->tags;
            }
        }
        
        // Save to database
        try {
            $emailTemplate = $this->emailTemplateRepository->update($data, $id);
            
            // Verify after save
            $savedTemplate = $this->emailTemplateRepository->find($id);
            
            if ($emailTemplate->content !== $savedTemplate->content) {
                session()->flash('error', 'Lỗi lưu template: Nội dung không khớp');
                return back()->withInput();
            }

            session()->flash('success', trans('email_template_extended::app.templates.messages.update-success'));
            
            return redirect()->route('admin.email_templates.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Lỗi khi cập nhật template: ' . $e->getMessage());
            
            return back()->withInput();
        }
    }
    /**
     * Xóa template email
     */
    public function destroy(int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
        
        Event::dispatch('email_template.delete.before', $id);
        
        $emailTemplate->forceDelete();
        
        Event::dispatch('email_template.delete.after', $id);
        
        return response()->json([
            'message' => trans('email_template_extended::app.templates.messages.delete-success'),
        ]);
    }

    /**
     * Xóa hàng loạt mẫu email
     */
    public function massDelete(Request $request)
    {
        $indices = $request->input('indices', []);
        
        foreach ($indices as $id) {
            $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
            $emailTemplate->forceDelete();
        }
        
        return response()->json([
            'message' => trans('email_template_extended::app.templates.messages.delete-success'),
        ]);
    }

    /**
     * Preview template with current/draft data
     */
    public function preview(Request $request, int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
        
        // Nếu có data từ form (draft preview), override template data
        if ($request->isMethod('post')) {
            // Override với data từ form
            if ($request->has('name')) {
                $emailTemplate->name = $request->input('name');
            }
            if ($request->has('subject')) {
                $emailTemplate->subject = $request->input('subject');
            }
            if ($request->has('preview_text')) {
                $emailTemplate->preview_text = $request->input('preview_text');
            }
            if ($request->has('content')) {
                $emailTemplate->content = $request->input('content');
            }
            if ($request->has('editor_mode')) {
                $emailTemplate->editor_mode = $request->input('editor_mode');
            }
            if ($request->has('builder_config')) {
                $emailTemplate->builder_config = $request->input('builder_config');
            }
        }
        
        // Get sample data
        $sampleData = $request->input('sample_data', $emailTemplate->sample_data ?? []);
        
        // Default sample data nếu chưa có
        if (empty($sampleData)) {
            $sampleData = [
                'customer_name' => 'Anna Nguyen',
                'company_name' => 'Marketbase',
                'email' => 'anna@example.com',
                'phone' => '+84 123 456 789',
                'address' => 'Hanoi, Vietnam',
                'order_number' => '#ORD-12345',
                'order_total' => '$299.00',
                'website_url' => 'https://marketbase.com',
                'product_name' => 'Premium Package',
                'current_year' => date('Y'),
            ];
        }
        
        // Render content với sample data
        $renderedContent = $this->renderTemplate($emailTemplate->content, $sampleData);
        $renderedSubject = $this->renderTemplate($emailTemplate->subject, $sampleData);

        return view('email_template_extended::preview', compact(
            'emailTemplate',
            'renderedContent',
            'renderedSubject',
            'sampleData'
        ))->with('isPreviewMode', true);
    }

    /**
     * Preview draft template (chưa lưu - từ trang create)
     */
    public function previewDraft(Request $request)
    {
        // Tạo object tạm từ form data
        $emailTemplate = new \stdClass();
        $emailTemplate->id = null;
        $emailTemplate->name = $request->input('name', 'Preview Template');
        $emailTemplate->subject = $request->input('subject', 'Preview Subject');
        $emailTemplate->preview_text = $request->input('preview_text', '');
        $emailTemplate->content = $request->input('content', '');
        $emailTemplate->builder_config = $request->input('builder_config', null);
        $emailTemplate->editor_mode = $request->input('editor_mode', 'classic');
        $emailTemplate->category = $request->input('category', 'general');
        $emailTemplate->locale = $request->input('locale', 'vi');
        $emailTemplate->created_at = now();
        $emailTemplate->updated_at = now();
        
        // Sample data để replace variables
        $sampleData = [
            'customer_name' => 'Anna',
            'company_name' => 'Marketbase',
            'email' => 'anna@example.com',
            'phone' => '+84 123 456 789',
            'address' => 'Hanoi, Vietnam',
            'order_number' => '#ORD-12345',
            'order_total' => '$299.00',
            'website_url' => 'https://marketbase.com',
        ];
        
        // Render content và subject với sample data
        $renderedSubject = $this->renderTemplate($emailTemplate->subject, $sampleData);
        $renderedContent = $this->renderTemplate($emailTemplate->content, $sampleData);
        
        return view('email_template_extended::preview', compact(
            'emailTemplate',
            'renderedSubject',
            'renderedContent',
            'sampleData'
        ))->with('isPreviewMode', true);
    }

    /**
     * Clone template
     */
    public function clone(int $id)
    {
        $originalTemplate = $this->emailTemplateRepository->findOrFail($id);
        $data = $originalTemplate->toArray();
        
        // Tạo tên unique
        $data['name'] = $data['name'] . ' (Copy - ' . date('YmdHis') . ')';
        
        // Modify cloned data
        $data['cloned_from_id'] = $originalTemplate->id;
        $data['user_id'] = auth()->guard('user')->id();
        
        unset($data['id'], $data['created_at'], $data['updated_at'], $data['deleted_at']);

        $clonedTemplate = $this->emailTemplateRepository->create($data);
        
        session()->flash('success', trans('email_template_extended::app.templates.messages.clone-success'));
        
        return redirect()->route('admin.email_templates.edit', $clonedTemplate->id);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
        $emailTemplate->toggleActive();
        
        return response()->json([
            'message' => trans('email_template_extended::app.templates.messages.status-updated'),
            'is_active' => $emailTemplate->is_active,
        ]);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
        $emailTemplate->incrementUsage();
        
        return response()->json([
            'message' => 'Usage tracked',
            'usage_count' => $emailTemplate->usage_count,
        ]);
    }

    /**
     * Get variables analysis
     */
    public function analyzeVariables(int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
        
        return response()->json([
            'defined' => $emailTemplate->getAvailableVariables(),
            'used' => $emailTemplate->getAllUsedVariables(),
            'undefined' => $emailTemplate->getUndefinedVariables(),
            'unused' => $emailTemplate->getUnusedVariables(),
            'has_issues' => $emailTemplate->hasUndefinedVariables(),
        ]);
    }

    /**
     * Export options (return JSON for modal)
     */
    public function export(int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
        
        return response()->json([
            'template' => [
                'id' => $emailTemplate->id,
                'name' => $emailTemplate->name,
                'category' => $emailTemplate->category_label,
                'editor_mode' => ucfirst($emailTemplate->editor_mode ?? 'classic'),
            ]
        ]);
    }

    /**
     * Export as ZIP package
     */
    public function exportZip(int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
        
        $zip = new \ZipArchive();
        $filename = storage_path('app/temp/' . \Illuminate\Support\Str::slug($emailTemplate->name) . '-' . date('Y-m-d') . '.zip');
        
        // Create temp directory if not exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        if ($zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            
            // 1. Add HTML file
            $htmlContent = view('email_template_extended::export-html', [
                'emailTemplate' => $emailTemplate,
                'renderedContent' => $this->renderTemplate($emailTemplate->content, $emailTemplate->sample_data ?? []),
                'renderedSubject' => $this->renderTemplate($emailTemplate->subject, $emailTemplate->sample_data ?? []),
                'sampleData' => $emailTemplate->sample_data ?? []
            ])->render();
            $zip->addFromString('template.html', $htmlContent);
            
            // 2. Add JSON config
            $jsonData = [
                'name' => $emailTemplate->name,
                'subject' => $emailTemplate->subject,
                'content' => $emailTemplate->content,
                'category' => $emailTemplate->category,
                'locale' => $emailTemplate->locale,
                'tags' => $emailTemplate->tags,
                'variables' => $emailTemplate->variables,
                'preview_text' => $emailTemplate->preview_text,
                'editor_mode' => $emailTemplate->editor_mode ?? 'classic',
                'builder_config' => $emailTemplate->builder_config,
            ];
            $zip->addFromString('config.json', json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // 3. Add README
            $readme = "# Email Template: {$emailTemplate->name}\n\n";
            $readme .= "## Files Included:\n";
            $readme .= "- template.html: Ready-to-use HTML email\n";
            $readme .= "- config.json: Template configuration and metadata\n\n";
            $readme .= "## How to Use:\n";
            $readme .= "1. Open template.html in your email service\n";
            $readme .= "2. Use config.json to import into builder\n\n";
            $readme .= "Exported at: " . now()->toDateTimeString() . "\n";
            $zip->addFromString('README.md', $readme);
            
            $zip->close();
            
            return response()->download($filename)->deleteFileAfterSend(true);
        }
        
        return back()->with('error', 'Could not create ZIP file');
    }

    /**
     * Export template as HTML
     */
    public function exportHtml(int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);

        $sampleData = $emailTemplate->sample_data ?? [];
        $renderedContent = $this->renderTemplate($emailTemplate->content, $sampleData);
        $renderedSubject = $this->renderTemplate($emailTemplate->subject, $sampleData);

        $html = view('email_template_extended::export-html', compact(
            'emailTemplate',
            'renderedContent',
            'renderedSubject',
            'sampleData'
        ))->render();

        $filename = \Illuminate\Support\Str::slug($emailTemplate->name) . '-' . date('Y-m-d') . '.html';
        
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Export template as JSON (bao gồm cả builder_config)
     */
    public function exportJson(int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);

        $data = [
            'name' => $emailTemplate->name,
            'subject' => $emailTemplate->subject,
            'content' => $emailTemplate->content,
            'category' => $emailTemplate->category,
            'locale' => $emailTemplate->locale,
            'tags' => $emailTemplate->tags,
            'variables' => $emailTemplate->variables,
            'preview_text' => $emailTemplate->preview_text,
            'editor_mode' => $emailTemplate->editor_mode ?? 'classic',
            'builder_config' => $emailTemplate->builder_config,
            'exported_at' => now()->toDateTimeString(),
            'exported_by' => auth()->guard('user')->user()->name ?? 'Unknown',
        ];

        $filename = \Illuminate\Support\Str::slug($emailTemplate->name) . '-' . date('Y-m-d') . '.json';
        
        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Import template from JSON
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:json',
        ]);

        $file = $request->file('file');
        $json = json_decode(file_get_contents($file->getRealPath()), true);

        if (!$json || !isset($json['name'], $json['subject'], $json['content'])) {
            return back()->with('error', trans('email_template_extended::app.templates.import-error'));
        }

        // Make name unique
        $originalName = $json['name'];
        $counter = 1;
        while ($this->emailTemplateRepository->findByField('name', $json['name'])->isNotEmpty()) {
            $json['name'] = $originalName . ' (' . $counter . ')';
            $counter++;
        }

        $json['user_id'] = auth()->guard('user')->id();
        
        // Đảm bảo editor_mode được set
        if (!isset($json['editor_mode'])) {
            $json['editor_mode'] = isset($json['builder_config']) && $json['builder_config'] 
                ? 'pro' 
                : 'classic';
        }

        $emailTemplate = $this->emailTemplateRepository->create($json);

        session()->flash('success', trans('email_template_extended::app.templates.messages.import-success'));
        
        return redirect()->route('admin.email_templates.edit', $emailTemplate->id);
    }

    /**
     * Compile template (render với variables)
     */
    public function compile(Request $request, int $id)
    {
        $emailTemplate = $this->emailTemplateRepository->findOrFail($id);
        
        $data = $request->input('data', []);
        
        $compiledContent = $this->renderTemplate($emailTemplate->content, $data);
        $compiledSubject = $this->renderTemplate($emailTemplate->subject, $data);

        return response()->json([
            'subject' => $compiledSubject,
            'content' => $compiledContent,
        ]);
    }

    /**
     * Process variables from request
     */
    protected function processVariables($variables)
    {
        if (is_string($variables)) {
            return json_decode($variables, true);
        }
        
        return $variables;
    }

    /**a
     * Render template with data
     */
    protected function renderTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        
        return $template;
    }

    /**
     * Extract chỉ email content, bỏ UI builder
     */
    private function extractEmailContent($html)
    {
        if (empty($html)) {
            return $html;
        }

        // Method 1: Tìm table email chính (role="presentation" hoặc cellpadding="0")
        if (preg_match('/<table[^>]*(?:role="presentation"|cellpadding="0")[^>]*>.*?<\/table>/is', $html, $matches)) {
            return $matches[0];
        }
        
        // Method 2: Lấy body content và clean
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
            $bodyContent = $matches[1];
            
            // Loại bỏ các div wrapper của builder UI
            $patterns = [
                '/<div[^>]*class="[^"]*(?:builder|wrapper|chrome|toolbar|inspector|styles)[^"]*"[^>]*>.*?<\/div>/is',
                '/<nav[^>]*>.*?<\/nav>/is',
                '/<header[^>]*style="[^"]*background[^"]*"[^>]*>.*?<\/header>/is',
                '/<script\b[^>]*>.*?<\/script>/is',
            ];
            
            foreach ($patterns as $pattern) {
                $bodyContent = preg_replace($pattern, '', $bodyContent);
            }
            
            return trim($bodyContent);
        }
        
        // Method 3: Tìm div hoặc table có nội dung nhiều text nhất
        if (preg_match_all('/<(?:table|div)[^>]*>(.*?)<\/(?:table|div)>/is', $html, $matches)) {
            $maxLength = 0;
            $bestContent = '';
            
            foreach ($matches[0] as $match) {
                $textLength = strlen(strip_tags($match));
                // Bỏ qua nếu có class builder/wrapper
                if (preg_match('/class="[^"]*(?:builder|wrapper|chrome)[^"]*"/', $match)) {
                    continue;
                }
                
                if ($textLength > $maxLength && $textLength > 100) {
                    $maxLength = $textLength;
                    $bestContent = $match;
                }
            }
            
            if (!empty($bestContent)) {
                return $bestContent;
            }
        }
        
        // Fallback: return original
        return $html;
    }
}