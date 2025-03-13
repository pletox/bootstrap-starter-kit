<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profile()
    {
        return view('settings.profile');
    }

    public function destroy(Request $request)
    {
        $user = User::find(\auth()->id());

        $request->validate([
            'password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The provided password is incorrect.');
                    }
                },
            ],
        ]);

        $user->delete();
        Auth::logout();

        return response()->json([
            'message' => 'Your account has been deleted successfully.',
            'status'=>200
        ]);
    }

    public  function passwordUpdate()
    {
        return view('settings.password-update');
    }

    public  function appearance()
    {
        return view('settings.appearance');
    }
}
