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
            'imepriimek' => 'required|regex:/^[\\s\\p{L}]+$/u',
            'datumrojstva' => 'required|date|before:12-4-2000|after:12-4-1900',
            'spol' => 'required|regex:/^[MmZzŽž]*/',
            'emso' => 'required|size:13|regex:/(^[0-9]*$)/',
            'davcna' => 'size:8|regex:/(^[0-9]*$)/',
            'email' => 'required|email',
            'naslovstalno' => 'required',
            'vrocanje' => 'required'
		];
	}

    public function messages()
    {
        return [
            'vstevilka.required' => 'Vpišite vpisno številko!',
            'vstevilka.numeric' => 'Napačna vpisna številka!',
            'imepriimek.required' => 'Vpišite ime in priimek!',
            'datumrojstva.required' => 'Vpišite datum rojstva!',
            'datumrojstva.date' => 'Napačen format datuma!',
            'datumrojstva.before' => 'Napačen datum rojstva!',
            'datumrojstva.after' => 'Napačen datum rojstva!',
            'spol.required' => 'Vpišite vaš spol!',
            'spol.regex' => 'Napačen spol!',
            'emso.required' => 'Vpišite emšo!',
            'emso.size' => 'Napačna dolžina emšo-ja!',
            'davcna' => 'Napačna dolžina davčne številke!',
            'email.required' => 'Vpišite vaš email!',
            'naslovstalno.required' => 'Vpišite vaš stalni naslov!'
        ];
    }

}
