<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * Get the validation rule message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.regex' => 'Please enter valid name.',
            'email.exists' => 'Entered email is already exists in our records!.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|regex:/[a-zA-Z]/|max:255',
            'email' => 'required|string|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|max:255|unique:users,email,'.$this->user,
            'doj' => 'required|date',
            'dol' => 'nullable|date|after_or_equal:doj',
            'stillWorking' => 'nullable|boolean',
            'profile' => 'sometimes|image|max:2048',
        ];
    }
}
