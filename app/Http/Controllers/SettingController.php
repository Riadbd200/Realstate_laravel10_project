<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmtpSetting;
use App\Models\SiteSetting;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    public function smtpSetting()
    {
        $setting = SmtpSetting::find(1);
        return view('admin.setting.smtp_update', compact('setting'));
    } //End Method


    public function updateSmtpSetting(Request $request)
    {
        $smtp_id = $request->id;

        SmtpSetting::findOrFail($smtp_id)->update([
            'mailer'       => $request->mailer,
            'host'         => $request->host,
            'port'         => $request->port,
            'username'     => $request->username,
            'password'     => $request->password,
            'encryption'   => $request->encryption,
            'from_address' => $request->from_address,
        ]);

        $notification = array(
            'message'    =>'SMTP Setting Updated Successfully!',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);


    } //End Method



    //Site Setting Method
    public function siteSetting()
    {
        $siteSetting = SiteSetting::find(1);
        return view('admin.setting.site_update', compact('siteSetting'));

    } //End Method


    public function updateSiteSetting(Request $request)
    {
        $siteId = $request->id; 

        if($request->file('logo')){

            $image = $request->file('logo');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(1500,386)->save('upload/logo/'.$name_gen);
            $save_url = 'upload/logo/'.$name_gen; 

            SiteSetting::findOrFail($siteId )->update([
                'support_phone'    => $request->support_phone,
                'company_address'  => $request->company_address,
                'email'            => $request->email,
                'facebook'         => $request->facebook,
                'twitter'          => $request->twitter,
                'copyright'        => $request->copyright,
                'logo'             =>$save_url
            ]);

            $notification = array(
                'message'    =>'Site Setting Updated With Logo Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('site.setting')->with($notification);

        }else{
            SiteSetting::findOrFail($siteId )->update([
                'support_phone'    => $request->support_phone,
                'company_address'  => $request->company_address,
                'email'            => $request->email,
                'facebook'         => $request->facebook,
                'twitter'          => $request->twitter,
                'copyright'        => $request->copyright   
            ]);

            $notification = array(
                'message'    =>'Site Setting Updated Without Logo Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('site.setting')->with($notification);
        } //End if 

    } //End Method

}
    
