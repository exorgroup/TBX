<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Only allow access if user can create or update a tax
        return backpack_user() &&
            (backpack_user()->can('Tax_Create') ||
                backpack_user()->can('Tax_Update'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'TaxName' => 'required|string|max:20',
            'TaxRate' => 'required|numeric|min:0|max:100|decimal:0,2',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'TaxName' => 'Tax Name',
            'TaxRate' => 'Tax Rate',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'TaxName.required' => 'The Tax Name field is required.',
            'TaxName.max' => 'The Tax Name may not be greater than 20 characters.',
            'TaxRate.required' => 'The Tax Rate field is required.',
            'TaxRate.numeric' => 'The Tax Rate must be a number.',
            'TaxRate.min' => 'The Tax Rate must be at least 0.',
            'TaxRate.max' => 'The Tax Rate may not be greater than 100.',
            'TaxRate.decimal' => 'The Tax Rate must have 2 decimal places or fewer.',
        ];
    }
}
