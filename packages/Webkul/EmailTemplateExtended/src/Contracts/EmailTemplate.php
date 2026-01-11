<?php

namespace Webkul\EmailTemplateExtended\Contracts;

use Webkul\EmailTemplate\Contracts\EmailTemplate as CoreEmailTemplateContract;

interface EmailTemplate extends CoreEmailTemplateContract
{
    public function incrementUsage();

    public function getAvailableVariables(): array;

    public function extractVariablesFromContent(): array;

    public function getAllUsedVariables(): array;

    public function hasUndefinedVariables(): bool;

    public function getUndefinedVariables(): array;

    public function toggleActive();

    public function addTag(string $tag);

    public function removeTag(string $tag);

    public function hasTag(string $tag): bool;

    public static function getCategories();

    public static function getCategoryLabels();

    public static function getVariableTypes();
}
