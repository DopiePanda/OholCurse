<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\TimezoneUpdateRequest;
use App\Http\Requests\BadgeUpdateRequest;
use App\Http\Requests\BackgroundUpdateRequest;
use App\Http\Requests\ThemeUpdateRequest;
use App\Http\Requests\DarkmodeUpdateRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Timezone;
use App\Models\WebsiteBackground;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'timezones' => Timezone::all(),
            'backgrounds' => WebsiteBackground::where('enabled', 1)->orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's timezone.
     */
    public function updateTimezone(TimezoneUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->timezone = $validated['timezone'];
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'timezone-updated');
    }

    /**
     * Update the user's background.
     */
    public function updateBadges(BadgeUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->show_badges = $validated['show_badges'];
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'badges-updated');
    }

    /**
     * Update the user's background.
     */
    public function updateBackground(BackgroundUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->background = $validated['background'];
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'background-updated');
    }

    /**
     * Update the user's theme.
     */
    public function updateTheme(ThemeUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->theme = $validated['theme'];
        
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'theme-updated');
    }

    /**
     * Update the user's darkmode preference.
     */
    public function updateDarkmode(DarkmodeUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->darkmode = $validated['darkmode'];
        
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'darkmode-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
