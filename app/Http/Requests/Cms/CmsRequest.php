<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CmsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $table       = $this->get('cms_table');
        $resourceKey = $this->get('resource');
        $model       = $this->route($resourceKey);
        $id          = $model?->getKey();   

        $rules = [
            'name'    => [
                'required',
                'string',
                'max:255',
                Rule::unique($table, 'name')->ignore($id),
            ],
            'remarks' => ['nullable', 'string',  'max:255'],
        ];
        
        return array_merge($rules, $this->getAdditionalRulesForTable($table));
    }
    
    protected function getAdditionalRulesForTable(?string $table): array
    {
        return match ($table) {
            'criterias' => [
                'no_of_participants' => ['required', 'numeric', 'min:1'],
            ],
            'citeria_details' => [
                'criteria_id' => ['required', 'numeric', 'exists:criterias,id'],
            ],
            default => [],
        };
    }
}
