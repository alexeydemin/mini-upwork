<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Vacancy;

class IndexVacancyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date' => 'date_format:Y-m-d',
            'month' => 'date_format:Y-m',
            'week' => 'integer|between:1,53',
            'sort' => 'in:' . Vacancy::DATE . ',' . Vacancy::RESPONSES,
            'dir' => 'in:asc,desc',
        ];
    }
}
