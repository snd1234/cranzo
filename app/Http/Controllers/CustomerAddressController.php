<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerAddressController extends BaseController
{
    public function index()
    {
        $addresses = DB::table('address')->where('user_id', Auth::id())->orderByDesc('id')->get();
        $states = DB::table('state')->where('status', 1)->orderBy('state_name')->get()->keyBy('id');

        return view('account.addresses', compact('addresses', 'states'));
    }

    public function store(Request $request)
    {
        if (DB::table('address')->where('user_id', Auth::id())->count() >= 10) {
            return back()->withErrors(['address' => 'You can save up to 10 addresses. Remove one before adding another.'])->withInput();
        }

        $data = $this->validated($request);
        DB::transaction(function () use ($data, $request) {
            $id = ((int) DB::table('address')->lockForUpdate()->max('id')) + 1;
            $makeDefault = $request->boolean('default_shipping')
                || ! DB::table('address')->where('user_id', Auth::id())->where('default_type', 1)->exists();
            if ($makeDefault) {
                DB::table('address')->where('user_id', Auth::id())->update(['default_type' => 2]);
            }
            DB::table('address')->insert($data + [
                'id' => $id,
                'user_id' => Auth::id(),
                'address_type' => 1,
                'default_type' => $makeDefault ? 1 : 2,
                'created_date' => now(),
            ]);
        });

        return back()->with('success', 'Address saved.');
    }

    public function update(Request $request, int $address)
    {
        $this->own($address);
        $data = $this->validated($request);
        DB::transaction(function () use ($address, $data, $request) {
            $makeDefault = $request->boolean('default_shipping');
            if ($makeDefault) {
                DB::table('address')->where('user_id', Auth::id())->update(['default_type' => 2]);
            }
            DB::table('address')->where('id', $address)->where('user_id', Auth::id())->update($data + [
                'default_type' => $makeDefault ? 1 : 2,
            ]);
        });

        return back()->with('success', 'Address updated.');
    }

    public function destroy(int $address)
    {
        $this->own($address);
        $used = DB::table('orders')->where('address_id', $address)->exists();
        if ($used) {
            return back()->withErrors(['address' => 'This address is already used on an order and was kept.']);
        }
        DB::table('address')->where('id', $address)->where('user_id', Auth::id())->delete();

        return back()->with('success', 'Address removed.');
    }

    private function validated(Request $request): array
    {
        $request->merge([
            'name' => $this->plain($request->input('name')),
            'mobile_number' => preg_replace('/\s+/', '', $this->plain($request->input('mobile_number'))) ?? '',
            'address' => $this->plain($request->input('address')),
            'locality' => $this->plain($request->input('locality')),
            'city' => $this->plain($request->input('city')),
            'pincode' => $this->plain($request->input('pincode')),
        ]);

        return $request->validate([
            'name' => ['required', 'string', 'max:128', 'regex:/^[\p{L}\s.\'-]{2,128}$/u'],
            'mobile_number' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'address' => ['required', 'string', 'max:256'],
            'locality' => ['required', 'string', 'max:256'],
            'city' => ['required', 'string', 'max:30', 'regex:/^[\p{L}\s.\'-]{2,30}$/u'],
            'state_id' => ['required', 'integer', Rule::exists('state', 'id')->where('status', 1)],
            'pincode' => ['required', 'regex:/^[1-9][0-9]{5}$/'],
        ], [
            'name.regex' => 'Enter the recipient name using letters only.',
            'mobile_number.regex' => 'Enter a 10-digit Indian mobile number.',
            'city.regex' => 'Enter a city name using letters only.',
            'state_id.exists' => 'Select a state from the list.',
            'pincode.regex' => 'Enter a 6-digit PIN code.',
        ]);
    }

    private function plain(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }

    private function own(int $id): object
    {
        $row = DB::table('address')->where('id', $id)->where('user_id', Auth::id())->first();
        abort_if(! $row, 404);

        return $row;
    }
}
