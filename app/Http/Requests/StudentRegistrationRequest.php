<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'step' => 'required|in:1,2,3,4',

            // STEP 1
            'profile' => 'required_if:step,1|image|mimes:jpg,jpeg,png|max:2048',
            'academic_year' => 'required_if:step,1|string',
            'academic_year_id' => 'required_if:step,1|exists:academic_years,id',
            'major' => 'required_if:step,1|in:computer science,computer technology,CST',
            'roll_no' => 'required_if:step,1|string',

            // STEP 2
            'matriculation_result' => 'required_if:step,2|image|mimes:jpg,jpeg,png|max:2048',
            'matriculation_certificate' => 'required_if:step,2|image|mimes:jpg,jpeg,png|max:2048',
            'last_academic_year' => 'required_if:step,2|string',
            'reg_email' => 'required_if:step,2|email',
            'phone' => ['required_if:step,2', 'string', 'regex:/^09(2|4|6|7|8|9)[0-9]{7,9}$/'],
            'dob' => 'required_if:step,2|date',
            'present_address' => 'required_if:step,2|string',

            'nrc_student' => 'required_if:step,2|string',
            'nrc_student_front' => 'required_if:step,2|image|mimes:jpg,jpeg,png|max:2048',
            'nrc_student_back' => 'required_if:step,2|image|mimes:jpg,jpeg,png|max:2048',

            'race' => 'required_if:step,2|string',
            'religion' => 'required_if:step,2|string',
            'blood_type' => 'required_if:step,2|string',

            'father_name' => 'required_if:step,2|string',
            'nrc_father_front' => 'required_if:step,2|image|mimes:jpg,jpeg,png|max:2048',
            'nrc_father_back' => 'required_if:step,2|image|mimes:jpg,jpeg,png|max:2048',
            'father_job' => 'required_if:step,2|string',

            'mother_name' => 'required_if:step,2|string',
            'nrc_mother_front' => 'required_if:step,2|image|mimes:jpg,jpeg,png|max:2048',
            'nrc_mother_back' => 'required_if:step,2|image|mimes:jpg,jpeg,png|max:2048',
            'mother_job' => 'required_if:step,2|string',

            'family_member_docs' => 'required_if:step,2|image|mimes:jpg,jpeg,png|max:2048',
            'permanent_address' => 'required_if:step,2|string',

            'guardian_name' => 'required_if:step,2|string',
            'guardian_relation' => 'required_if:step,2|string',
            'guardian_job' => 'required_if:step,2|string',
            'guardian_phone' => ['required_if:step,2', 'string', 'regex:/^09(2|4|6|7|8|9)[0-9]{7,9}$/'],

            // STEP 3
            'payment_method' => 'required_if:step,3|string',
            'payment_screenshot' => 'required_if:step,3|image|mimes:jpg,jpeg,png|max:2048',
            'transaction_id' => 'required_if:step,3|digits:6',
            'payment_note' => 'nullable|string',

            // STEP 4
            'agree_rules' => 'required_if:step,4|accepted',
        ];
    }
}
