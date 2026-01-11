<?php

namespace Webkul\EmailTemplateExtended\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Collection;

class EmailTemplateRepository extends Repository
{
    public function model(): string
    {
        return \Webkul\EmailTemplateExtended\Models\EmailTemplate::class;
    }

    // Lấy tất cả các template đang hoạt động 
    public function getActiveTemplates(): Collection
    {
        return $this->model->active()->get();
    }

    // Lấy tempale theo danh mục 
    public function getByCategory(string $category): Collection
    {
        return $this->model->category($category)->active()->get();
    }

    // Lấy các tempalate phổ bién 
    public function getPopularTemplates(int $limit = 10): Collection
    {
        return $this->model->popular($limit)->active()->get();
    }

    // Lấy các template gần đây 
    public function getRecentTemplates(int $limit = 10): Collection
    {
        return $this->model->recent($limit)->active()->get();
    }

    // Lấy template theo ngôn ngữ 
    public function getByLocale(string $locale): Collection
    {
        return $this->model->locale($locale)->active()->get();
    }

    // Search template
    public function search(string $query): Collection
    {
        return $this->model->search($query)->active()->get();
    }

    // Lấy template theo tag 
    public function getByTag(string $tag): Collection
    {
        return $this->model->withTag($tag)->active()->get();
    }

    // Lấy tất cả các tag duy nhất từ các template 
    public function getAllTags(): array
    {
        $templates = $this->model->whereNotNull('tags')->get();
        $allTags = [];
        foreach ($templates as $template) {
            if (is_array($template->tags)) {
                $allTags = array_merge($allTags, $template->tags);
            }
        }
        return array_values(array_unique($allTags));
    }

    // Lấy các mẫu có các biến chua được định nghĩa 
    public function getTemplatesWithIssues(): Collection
    {
        return $this->model->get()->filter(function ($template) {
            return $template->hasUndefinedVariables();
        });
    }

    // Xóa template
    public function cloneTemplate(int $id, array $overrides = []): object
    {
        $original = $this->findOrFail($id);
        $data = $original->toArray();
        // Xóa các trường không cho phéo copy 
        unset(
            $data['id'],
            $data['created_at'],
            $data['updated_at'],
            $data['deleted_at'],
            $data['usage_count'],
            $data['last_used_at']
        );
        $data['cloned_from_id'] = $original->id;
        $data = array_merge($data, $overrides);
        if (!isset($overrides['name'])) {
            $data['name'] = $this->makeNameUnique($data['name'] . ' (Copy)');
        }
        return $this->create($data);
    }

    // đảm bảo tính toàn vẹn của một tên mẫu 
    protected function makeNameUnique(string $name): string
    {
        $originalName = $name;
        $counter = 1;
        while ($this->findByField('name', $name)->isNotEmpty()) {
            $name = $originalName . ' (' . $counter . ')';
            $counter++;
        }
        return $name;
    }

    // Trả về 1 template đầy đủ thông tin 
    public function getWithRelations(int $id): object
    {
        return $this->model
            ->with(['user', 'clonedFrom', 'clones'])
            ->findOrFail($id);
    }

    /**
     * Bulk activate templates
     */
    public function bulkActivate(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->update(['is_active' => true]);
    }

    /**
     * Bulk deactivate templates
     */
    public function bulkDeactivate(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->update(['is_active' => false]);
    }

    /**
     * Bulk delete templates
     */
    public function bulkDelete(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    // Hiển thị số lượng thống kê 
    public function getStatistics(): array
    {
        $model = $this->model;
        
        return [
            'total' => $model->count(),
            'active' => $model->active()->count(),
            'inactive' => $model->where('is_active', false)->count(),
            'by_category' => $model->groupBy('category')
                ->selectRaw('category, count(*) as count')
                ->pluck('count', 'category')
                ->toArray(),
            'by_locale' => $model->groupBy('locale')
                ->selectRaw('locale, count(*) as count')
                ->pluck('count', 'locale')
                ->toArray(),
            'most_used' => $model->orderBy('usage_count', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'usage_count']),
            'recently_used' => $model->whereNotNull('last_used_at')
                ->orderBy('last_used_at', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'last_used_at']),
        ];
    }

    /**
     * Find template by name
     */
    public function findByName(string $name): ?object
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Update template variables
     */
    public function updateVariables(int $id, array $variables): object
    {
        return $this->update(['variables' => $variables], $id);
    }

    /**
     * Add tag to template
     */
    public function addTag(int $id, string $tag): object
    {
        $template = $this->findOrFail($id);
        $template->addTag($tag);
        
        return $template->fresh();
    }

    /**
     * Remove tag from template
     */
    public function removeTag(int $id, string $tag): object
    {
        $template = $this->findOrFail($id);
        $template->removeTag($tag);
        
        return $template->fresh();
    }

    /**
     * Sync tags for template
     */
    public function syncTags(int $id, array $tags): object
    {
        $template = $this->findOrFail($id);
        $template->syncTags($tags);
        
        return $template->fresh();
    }
}