<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dep_name' =>  'required',
            'dep_manager_id' => 'required',
        ];
    }
    
    public function messages()
    {
        return [
            'dep_name.required' => 'Department Name field is Required.',
            'dep_manager_id.required' => 'Manager field is Required.'
        ];
    }
}
