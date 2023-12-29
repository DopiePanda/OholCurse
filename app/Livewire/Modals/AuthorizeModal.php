<?php

namespace App\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

use App\Models\AuthAttempt;
use App\Models\User;
use App\Models\Timezone;

use App\Providers\RouteServiceProvider;

class AuthorizeModal extends ModalComponent
{

    public  $email;
    public  $challenge;
    public  $hash;

    public $response;

    public $timezones;

    public $username;
    public $password;
    public $user_timezone;

    public $validated;
    public $validationError;
    public $emailInUse;

    private $maxAuthAttempts = 10;
    private $authLockoutTime = 60;

    public function mount()
    {
        $this->validated = false;
        $this->emailInUse = false;
        $this->challenge = 'oholcurse'.Str::random(20);

        $this->timezones = Timezone::Orderby('offset')->get();
        $this->user_timezone = 'Europe/London';
    }

    public function render()
    {
        return view('livewire.modals.authorize-modal');
    }

    public function authorizeUser()
    {
        $this->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'challenge' => ['required', 'string', 'size:29'],
            'hash' => ['required', 'string', 'size:40'],
        ]);

        if($this->isNotOverAttemptLimit() < $this->maxAuthAttempts)
        {
 
            if($this->verifyUniqueChallenge() == 0)
            {
                $url = "http://onehouronelife.com/ticketServer/server.php?action=check_ticket_hash&email=$this->email&hash_value=$this->hash&string_to_hash=$this->challenge";
        
                $response = Http::get($url);
                $this->response = $response->body();
                $this->handleResponse();
            }
            else
            {
                $this->validated = false;
                $this->registerAuthAttempt('CHALLENGE_USED');
                $this->validationError = 'Challenge previously used, try again with a new challenge.';
                $this->challenge = 'oholcurse'.Str::random(20);
            }
        }
        else
        {
            $this->validated = false;
            $this->registerAuthAttempt('AUTH_LIMIT');
            $this->validationError = 'Too many failed authorization attempts. Please try again in one hour.';
        }
    }

    private function verifyUniqueChallenge()
    {
        $count = AuthAttempt::where('email', $this->email)
                                ->where('challenge', $this->challenge)
                                ->count();
        return $count;
    }

    private function isNotOverAttemptLimit()
    {
        $from = now()->subMinutes($this->authLockoutTime);
        $to = now();

        $count = AuthAttempt::where('email', $this->email)
                                ->where('response', 'INVALID')
                                ->orWhere('response', 'CHALLENGE_USED')
                                ->whereBetween('created_at', [$from, $to])
                                ->count();
        return $count;
    }

    private function checkUserEmail()
    {
        return User::where('email', $this->email)->count();
    }

    public function handleResponse()
    {
        if($this->response == 'VALID')
        {
            $this->validated = true;
            $this->registerAuthAttempt('VALID');

            if($this->checkUserEmail() == 0)
            {
                
                $this->emailInUse = false;
            }
            else
            {
                $this->emailInUse = true;
    
                $user = User::where('email', $this->email)->first();
    
                $this->username = $user->username;
                $this->password = $user->password;
                $this->user_timezone = $user->timezone;
            }
        }
        else
        {
            $this->validated = false;
            $this->validationError = 'Could not verify.<br />Please double check your information.';
            $this->registerAuthAttempt('INVALID');
        }
    }

    private function registerAuthAttempt($response)
    {
        $ip = $this->getUserIp();

        $attempt = AuthAttempt::updateOrCreate([
            'email' => $this->email,
            'response' => $response,
        ],[
            'challenge' => $this->challenge,
            'hash' => $this->hash,
            'ip' => $ip,
        ]);
    }

    private function getUserIp()
    {
        $ipAddress = '';

        // Check for X-Forwarded-For headers and use those if found
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ('' !== trim($_SERVER['HTTP_X_FORWARDED_FOR']))) {
            $ipAddress = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
        } else {
            if (isset($_SERVER['REMOTE_ADDR']) && ('' !== trim($_SERVER['REMOTE_ADDR']))) {
                $ipAddress = trim($_SERVER['REMOTE_ADDR']);
            }
        }

        return $ipAddress;
    }

    public function createUser()
    {
        $this->validate([
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'user_timezone' => ['required', 'exists:timezones,name'],
        ]);

        $user = User::create([
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'timezone' => $this->user_timezone,
        ]);

        $this->registerAuthAttempt('USER_CREATED');

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function updateUser()
    {
        $this->validate([
            'username' => ['required', 'string', 'max:255', 'exists:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'exists:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'user_timezone' => ['required', 'exists:timezones,name'],
        ]);

        $user = User::where('email', $this->email)->first();

        if($user->password != $this->password)
        {
            if($user->password != Hash::make($this->password))
            {
                $user->password = Hash::make($this->password);
            }
        }

        if($user->timezone != $this->user_timezone)
        {
            $user->timezone = $this->user_timezone;
        }

        $user->save();

        $this->registerAuthAttempt('USER_UPDATED');

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }
}
