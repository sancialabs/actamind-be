<?php

namespace App\Http\Requests\Plans;

use App\Enum\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('plans', 'slug')->ignore($this->route('plan')?->id),
            ],
            'description' => ['sometimes', 'string'],
            'currency' => ['sometimes', new Enum(Currency::class)],
            'price_cents' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'limits' => ['sometimes', 'array'],
            'limits.journal_entries' => ['nullable', 'integer', 'min:0'],
            'limits.notes' => ['nullable', 'integer', 'min:0'],
            'limits.attachments_mb' => ['nullable', 'integer', 'min:0'],
            'limits.reminders' => ['nullable', 'integer', 'min:0'],
            'limits.pomodoro' => ['nullable', 'integer', 'min:0'],
            'limits.kanban_boards' => ['nullable', 'integer', 'min:0'],
            'limits.kanban_tasks' => ['nullable', 'integer', 'min:0'],
            'limits.publishing_platform' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The plan name must be text.',
            'slug.alpha_dash' => 'The slug may only contain letters, numbers, dashes, and underscores.',
            'slug.unique' => 'A plan with this slug already exists.',
            'currency.enum' => 'The selected currency is not supported.',
            'price_cents.min' => 'The price cannot be negative.',
        ];
    }
}
