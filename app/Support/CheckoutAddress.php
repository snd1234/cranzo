<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckoutAddress
{
    public static function fromRequest(Request $request): array
    {
        if ($request->filled('address_id')) {
            abort_unless(auth()->check(), 403);
            $saved = DB::table('address')
                ->where('id', $request->integer('address_id'))
                ->where('user_id', auth()->id())
                ->first();
            if (! $saved) {
                throw ValidationException::withMessages(['address_id' => 'Choose one of your saved addresses.']);
            }
            $data = [
                'name' => trim(strip_tags((string) $saved->name)),
                'mobile_number' => preg_replace('/\s+/', '', (string) $saved->mobile_number) ?? '',
                'address' => trim(strip_tags((string) $saved->address)),
                'locality' => trim(strip_tags((string) $saved->locality)),
                'city' => trim(strip_tags((string) $saved->city)),
                'state_id' => $saved->state_id,
                'pincode' => (string) $saved->pincode,
            ];
            $check = validator($data, self::rules(), self::messages());
            if ($check->fails()) {
                throw ValidationException::withMessages([
                    'address_id' => 'This saved address is incomplete. Choose another one or enter a new address.',
                ]);
            }

            return $data;
        }

        $request->merge([
            'name' => trim(strip_tags((string) $request->input('name'))),
            'mobile_number' => preg_replace('/\s+/', '', trim(strip_tags((string) $request->input('mobile_number')))) ?? '',
            'address' => trim(strip_tags((string) $request->input('address'))),
            'locality' => trim(strip_tags((string) $request->input('locality'))),
            'city' => trim(strip_tags((string) $request->input('city'))),
            'pincode' => trim(strip_tags((string) $request->input('pincode'))),
        ]);

        return $request->validate(self::rules(), self::messages());
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:128', 'regex:/^[\p{L}\s.\'-]{2,128}$/u'],
            'mobile_number' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'address' => ['required', 'string', 'max:256'],
            'locality' => ['required', 'string', 'max:256'],
            'city' => ['required', 'string', 'max:30', 'regex:/^[\p{L}\s.\'-]{2,30}$/u'],
            'state_id' => ['required', 'integer', Rule::exists('state', 'id')->where('status', 1)],
            'pincode' => ['required', 'regex:/^[1-9][0-9]{5}$/'],
        ];
    }

    public static function messages(): array
    {
        return [
            'name.regex' => 'Enter the recipient name using letters only.',
            'mobile_number.regex' => 'Enter a 10-digit Indian mobile number.',
            'city.regex' => 'Enter a city name using letters only.',
            'state_id.exists' => 'Select a state from the list.',
            'pincode.regex' => 'Enter a 6-digit PIN code.',
        ];
    }
}
