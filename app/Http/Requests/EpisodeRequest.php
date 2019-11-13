<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Program;

class EpisodeRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'program_id' => 'required|integer'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'program_id.required' => 'Escolha um programa!',
            'program_id.integer' => 'Escolha um programa!1',
        ];
    }
}
