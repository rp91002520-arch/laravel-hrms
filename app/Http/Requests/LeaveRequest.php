<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'employee_id' =>
            'required|exists:employees,id',

            'leave_type' =>
            'required|in:Paid,Sick,Casual',

            'from_date' =>
            'required|date',

            'to_date' =>
            'required|date|after_or_equal:from_date',

            'reason' => 'required'
        ];
    }
}