<?php

namespace App\Http\Controllers;

use App\AccountLog;
use App\Following;
use App\UserFilter;
use Auth;
use DB;
use Cache;
use Purify;
use Illuminate\Http\Request;
use App\Http\Controllers\Settings\{
    HomeSettings,
    PrivacySettings,
    SecuritySettings
};

class SettingsController extends Controller
{
    use HomeSettings,
    PrivacySettings,
    SecuritySettings;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function accessibility()
    {
        $settings = Auth::user()->settings;

        return view('settings.accessibility', compact('settings'));
    }

    public function accessibilityStore(Request $request)
    {
        $settings = Auth::user()->settings;
        $fields = [
          'compose_media_descriptions',
          'reduce_motion',
          'optimize_screen_reader',
          'high_contrast_mode',
          'video_autoplay',
      ];
        foreach ($fields as $field) {
            $form = $request->input($field);
            if ($form == 'on') {
                $settings->{$field} = true;
            } else {
                $settings->{$field} = false;
            }
            $settings->save();
        }

        return redirect(route('settings.accessibility'))->with('status', 'Settings successfully updated!');
    }

    public function notifications()
    {
        return view('settings.notifications');
    }

    public function applications()
    {
        return view('settings.applications');
    }

    public function dataExport()
    {
        return view('settings.dataexport');
    }

    public function exportFollowing()
    {
        $data = Cache::remember('account:export:profile:following:'.Auth::user()->profile->id, 1440, function() {
            return Auth::user()->profile->following()->get()->map(function($i) {
                return $i->url();
            });
        });
        return response()->streamDownload(function () use($data) {
            echo $data;
        }, 'following.json');
    }

    public function exportFollowers()
    {
        $data = Cache::remember('account:export:profile:followers:'.Auth::user()->profile->id, 1440, function() {
            return Auth::user()->profile->followers()->get()->map(function($i) {
                return $i->url();
            });
        });
        return response()->streamDownload(function () use($data) {
            echo $data;
        }, 'followers.json');
    }

    public function exportMuteBlockList()
    {
        $profile = Auth::user()->profile;
        $exists = UserFilter::select('id')
            ->whereUserId($profile->id)
            ->exists();
        if(!$exists) {
            return redirect()->back();
        }
        $data = Cache::remember('account:export:profile:muteblocklist:'.Auth::user()->profile->id, 1440, function() use($profile) {
            return json_encode([
                'muted' => $profile->mutedProfileUrls(),
                'blocked' => $profile->blockedProfileUrls()
            ], JSON_PRETTY_PRINT);
        });
        return response()->streamDownload(function () use($data) {
            echo $data;
        }, 'muted-and-blocked-accounts.json');
    }

    public function dataImport()
    {
        return view('settings.import.home');
    }

    public function dataImportInstagram()
    {
        return view('settings.import.instagram.home');
    }

    public function developers()
    {
        return view('settings.developers');
    }
}

