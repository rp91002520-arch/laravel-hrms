<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->employee?->id;

        return [

            'employee_code' =>
            'required|unique:employees,employee_code,' . $id,

            'name' => 'required',

            'email' =>
            'required|email|unique:employees,email,' . $id,

            'phone' => 'required',

            'department' => 'required',

            'joining_date' => 'required|date',
        ];
    }
}