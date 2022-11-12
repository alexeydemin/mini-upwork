<?php

namespace App\Http\Requests;

use Egulias\EmailValidator\Result\Reason\DotAtEnd;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Vacancy;

class IndexVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
