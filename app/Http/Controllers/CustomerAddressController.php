<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $data = $this->validated($request);
        DB::transaction(function () use ($data, $request) {
            $id = ((int) DB::table('address')->lockForUpdate()->max('id')) + 1;
            if ($request->boolean('default_shipping')) {
                DB::table('address')->where('user_id', Auth::id())->where('address_type', 1)->update(['default_type' => 2]);
            }
            DB::table('address')->insert($data + [
                'id' => $id,
                'user_id' => Auth::id(),
                'address_type' => 1,
                'default_type' => $request->boolean('default_shipping') ? 1 : 2,
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
            if ($request->boolean('default_shipping')) {
                DB::table('address')->where('user_id', Auth::id())->where('address_type', 1)->update(['default_type' => 2]);
            }
            DB::table('address')->where('id', $address)->update($data + [
                'default_type' => $request->boolean('default_shipping') ? 1 : 2,
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
        return $request->validate([
            'name' => ['required', 'string', 'max:128'],
            'mobile_number' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'address' => ['required', 'string', 'max:256'],
            'locality' => ['required', 'string', 'max:256'],
            'city' => ['required', 'string', 'max:30'],
            'state_id' => ['required', 'integer', 'exists:state,id'],
            'pincode' => ['required', 'digits:6'],
        ]);
    }

    private function own(int $id): object
    {
        $row = DB::table('address')->where('id', $id)->where('user_id', Auth::id())->first();
        abort_if(! $row, 404);

        return $row;
    }
}
