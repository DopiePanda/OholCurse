<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\TimezoneUpdateRequest;
use App\Http\Requests\ThemeUpdateRequest;
use App\Http\Requests\DarkmodeUpdateRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Timezone;

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
     * Update the user's theme.
     */
    public function updateTheme(ThemeUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if($validated['theme'] == 'auto')
        {
            $request->user()->darkmode = 'auto';
        }else
        {
            $request->user()->darkmode = $validated['theme'];
        }
        
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'theme-updated');
    }

    /**
     * Update the user's theme.
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
