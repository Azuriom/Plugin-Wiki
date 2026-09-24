<?php

namespace Azuriom\Plugin\Wiki\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the wiki settings page.
     */
    public function show()
    {
        return view('wiki::admin.settings', [
            'layout' => setting('wiki.layout', 'default'),
        ]);
    }

    /**
     * Update the settings.
     */
    public function save(Request $request)
    {
        $validated = $this->validate($request, [
            'layout' => ['required', 'in:default,documentation'],
        ]);

        Setting::updateSettings([
            'wiki.layout' => $validated['layout'],
        ]);

        ActionLog::log('wiki.settings.updated');

        return to_route('wiki.admin.settings')
            ->with('success', trans('messages.status.success'));
    }
}
