<?php

namespace App\Http\Requests;

use App\Models\Vacancy;
use Illuminate\Foundation\Http\FormRequest;

class StoreResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->id != Vacancy::find($this->vacancyId)->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'vacancyId' => 'required|unique:responses,vacancy_id,NULL,id,user_id,' . $this->user()->id,
            'text' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'vacancyId.unique' => 'You already responded to this vacancy',
        ];
    }
}
