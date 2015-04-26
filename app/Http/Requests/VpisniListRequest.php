<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class VpisniListRequest extends Request {



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
            'vstevilka' => 'required|numeric',
            'imepriimek' => 'required|regex:/(^[A-Za-z ]*$)/',
            'datumrojstva' => 'required|before:12.4.2000',
            'drzavarojstva' => 'required',
            'krajrojstva' => 'required',
            'drzavljanstvo' => 'required',
            'spol' => 'required',
            'emso' => 'required|size:13|regex:/(^[0-9]*$)/',
            'davcna' => 'size:8|regex:/(^[0-9]*$)/',
            'email' => 'required|email',
            'naslovstalno' => 'required',
            'posta' => 'required|size:4|regex:/(^[0-9]*$)/',
            'studiskiprogram' => 'required',
            'vrstastudija' => 'required',
            'vrstavpisa' => 'required',
            'letnikdodatno' => 'required',
            'nacin' => 'required',
            'oblika' => 'required'
		];
	}

    public function messages()
    {
        return [
            'vstevilka' => '',
            'imepriimek' => '',
            'datumrojstva' => '',
            'drzavarojstva' => '',
            'krajrojstva' => '',
            'drzavljanstvo' => '',
            'spol' => '',
            'emso' => '',
            'davcna' => '',
            'email' => '',
            'naslovstalno' => '',
            'posta' => '',
            'studiskiprogram' => '',
            'vrstastudija' => '',
            'vrstavpisa' => '',
            'letnikdodatno' => '',
            'nacin' => '',
            'oblika' => ''
        ];
    }

}
