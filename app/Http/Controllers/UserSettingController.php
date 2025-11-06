<?php

namespace App\Http\Controllers;
use App\Models\UserSetting;

use Illuminate\Http\Request;

class UserSettingController extends Controller
{

    public function index()
    {
        $userId = auth()->id();
        $defaultSettings = UserSetting::where('user_id',$userId);
        return view('user_settings', compact('defaultSettings'));
    }

    public function update(Request $request)
    {
        $userId = auth()->id();
        UserSetting::where('user_id',$userId)->first()->update([
                            'work_minutes' => $request->input('work_minutes'),
                            'short_break_minutes' => $request->input('short_break_minutes'),
                            'long_break_minutes' => $request->input('long_break_minutes'),
                            ]);

        return redirect('/settings')->with('success','Settings Updated!');
    }
}
