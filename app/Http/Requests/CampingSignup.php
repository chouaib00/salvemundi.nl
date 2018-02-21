<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    /**
     * Class CampingSignup
     *
     * @package App\Http\Requests
     */
    class CampingSignup extends FormRequest {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize() {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules() {
            return [
                'pcn'              => 'required|integer',
                'first_name'       => 'required|string|max:150',
                'last_name'        => 'required|string|max:150',
                'phone'            => 'required|max:15',
                'email'            => 'required|email|confirmed',
//                'agree_salvemundi' => 'required|boolean',
//                'agree_buitenjan' => 'required|boolean'
                'address'  => 'required|min:5|max:150',
                'city'     => 'required|min:3|max:150',
                'postal'   => 'required|string|size:6|regex:/^[0-9]{4}[A-Z]{2}$/',
                'remarks' => 'nullable|string|max:1500',
                'birthday' => 'required|date|before:-16 years',
            ];
        }

        /**
         * Get custom messages for validator errors.
         *
         * @return array
         */
        public function messages() {
            return [
                'agree_salvemundi.required' => trans('camping.signup.errors.agree_salvemundi'),
                'agree_buitenjan.required'  => trans('camping.signup.errors.agree_buitenjan'),
            ];
        }

        /**
         * Get custom attributes for validator errors.
         *
         * @return array
         */
        public function attributes() {
            return [
                'agree_salvemundi' => trans('camping.signup.terms'),
                'agree_buitenjan' => trans('camping.signup.terms')
            ];
        }
    }