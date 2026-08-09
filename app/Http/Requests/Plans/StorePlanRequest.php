<?php

namespace App\Http\Requests\Plans;

use App\Enum\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:plans,slug'],
            'description' => ['required', 'string'],
            'currency' => ['required', new Enum(Currency::class)],
            'price_cents' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'limits' => ['required', 'array'],
            'limits.journal_entries' => ['nullable', 'integer', 'min:0'],
            'limits.notes' => ['nullable', 'integer', 'min:0'],
            'limits.attachments_mb' => ['nullable', 'integer', 'min:0'],
            'limits.reminders' => ['nullable', 'integer', 'min:0'],
            'limits.pomodoro' => ['nullable', 'integer', 'min:0'],
            'limits.kanban_boards' => ['nullable', 'integer', 'min:0'],
            'limits.kanban_tasks' => ['nullable', 'integer', 'min:0'],
            'limits.publishing_platform' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide a name for this plan.',
            'slug.required' => 'Please provide a unique slug for this plan (e.g. "free", "pro").',
            'slug.alpha_dash' => 'The slug may only contain letters, numbers, dashes, and underscores.',
            'slug.unique' => 'A plan with this slug already exists.',
            'description.required' => 'Please provide a description for this plan.',
            'currency.required' => 'Please select a currency for this plan.',
            'currency.enum' => 'The selected currency is not supported.',
            'price_cents.required' => 'Please provide a price for this plan.',
            'price_cents.min' => 'The price cannot be negative.',
            'limits.required' => 'Please provide feature limits for this plan.',
            'limits.publishing_platform.required' => 'Please specify whether Publishing Platform is included.',
        ];
    }
}
