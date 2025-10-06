<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'criteria_title' => ['required', 'string', 'max:255'],
            'participants'   => ['required', 'integer', 'min:1', 'max:100000'],

            'remarks'        => ['nullable', 'string', 'max:500'],
            
            'criteria_name'  => ['required', 'array', 'min:1'],
            'criteria_name.*'=> ['required', 'string', 'max:255'],

            'percentage'     => ['required', 'array', 'min:1'],
            'percentage.*'   => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'criteria_name.required' => 'Please add at least one criteria row.',
            'percentage.required'    => 'Please add at least one criteria row with percentage.',
        ];
    }
    
    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $names = (array) $this->input('criteria_name', []);
            $percs = (array) $this->input('percentage', []);

            if (count($names) !== count($percs)) {
                $v->errors()->add('percentage', 'Criteria rows are inconsistent. Please make sure each row has both a name and a percentage.');
                return;
            }
            
            $total = array_reduce($percs, function ($carry, $p) {
                return $carry + (is_numeric($p) ? (float) $p : 0);
            }, 0.0);
            
            $total = round($total, 5);
            
            if (abs($total - 100) > 0.001) {
                $v->errors()->add('percentage', sprintf(
                    'The total percentage must be exactly 100%%. Your total is %.2f%%.',
                    $total
                ));
            }
        });
    }
    
    public function validatedPayload(): array
    {
        $data = $this->validated();

        return [
            'name'               => $data['criteria_title'],
            'no_of_participants' => (int) $data['participants'],
            'remarks'            => $data['remarks'] ?? null,
            'details'            => collect($data['criteria_name'])
                ->zip($data['percentage'])
                ->map(function ($pair) {
                    return [
                        'criteria_name' => (string) $pair[0],
                        'percentage'    => (int) $pair[1],
                    ];
                })->all(),
        ];
    }
}
