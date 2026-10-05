<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Setting;
use Illuminate\Http\Request;

class SettingController extends AdminController
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }
    
    public function get()
    {
        $data = array(
            'title' => 'Settings',
            'settings' => Setting::find(1)->settings,
        );
        return view('admin.settings')->with($data);
    }

    public function save(Request $request)
    {
        $setting = Setting::find(1);

        $data = array(
            'contact' => $request->contact,
            'social_links' => $request->socials,
            'business_hours' => $request->business_hours,
            'footer_about' => $request->footer_about,
        );
        $setting->settings = $data;
        $setting->save();

        return response()->json([
            'success' => "Settings have been updated successfully",
            'redirect' => route('admin.settings')
        ]);
    }
}
