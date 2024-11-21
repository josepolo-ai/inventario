<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceRequest extends FormRequest
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
            'ip' => 'required',
            'mac' => 'required',
            'port' => 'required',
            'office' => 'required',
            'dni' => 'required',
            'fullname' => 'required',
            'type' => 'required',
            'charge' => 'required',
            'is_ugel' => 'required',
            'connection_type' => 'required',
            'use_type' => 'required'
        ];
    }
    
    public function messages(): array
    {
        return [
            'ip.required' => 'IP',
            'mac.required' => 'MAC',
            'office.required' => 'OFICINA',
            'port.required' => 'PUERTO',
            'dni.required' => 'DNI',
            'fullname.required' => 'NOMBRE COMPLETO',
            'type.required' => 'TIPO',
            'charge.required' => 'CARGO',
            'is_ugel.required' => 'CARGO',
            'connection_type.required' => 'TIPO CONEXIÓN',
            'use_type.required' => 'TIPO DE USO'
        ];
    }
}
