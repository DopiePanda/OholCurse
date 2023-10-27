<div class="p-4" wire:ignore.self>
    @if($validated == true)
        @if($emailInUse == false)
        <h2>Create your account login</h2>
        
        <form wire:submit.prevent='createUser'>
            @csrf
            <div class="grid grid-rows-1">
                <div class="p-2">
                    <label class="block">Username</label>
                    <input wire:model="username" class="mt-2 w-full rounded-md border border-blue-400" type='text' name='email' placeholder="MyFancyUsername" />
                    @error('username') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                    <small>Enter a username for future login.</small>
                </div>
                <div class="p-2">
                    <label class="block">Password</label>
                    <input wire:model="password" class="mt-2 w-full rounded-md border border-blue-400" type='password' name='challenge' placeholder="**********" />
                    @error('password') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                    <small>Enter a secure password. Please use one not used on different services</small>
                </div>
                <div class="p-2">
                    <label class="block">Timezone</label>
                    <select wire:model="user_timezone">
                        @foreach($timezones as $timezone)
                            <option value="{{ $timezone->name }}">{{ $timezone->name }} ({{ $timezone->offset }})</option>
                        @endforeach 
                    </select>
                    @error('user_timezone') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                    <small>Select your local timezone for displaying and submitting reports. This can be changed later from your profile.</small>
                </div>

                <div class="p-2">
                    <button type="submit" class="p-4 w-full bg-blue-400 text-white">Create new user account</button>
                </div>
            </div>
        </form>
        @elseif($emailInUse == true)
        <h2>Select a new password for you account</h2>
        <form wire:submit.prevent='updateUser'>
            <div class="grid grid-rows-1">
                <div class="p-2">

                    <label class="block">Username</label>
                    <small>Previously selected username</small>

                    <input wire:model="username" class="mt-2 w-full rounded-md border border-blue-400" type='text' name='username' placeholder="MyUsername" disabled />
                    @error('username') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                    
                </div>
                <div class="p-2">

                    <label class="block">Password</label>
                    <small>Enter new secure password or leave as is to keep current password.</small>

                    <input wire:model="password" class="mt-2 w-full rounded-md border border-blue-400" type='password' name='password' placeholder="************" />
                    @error('password') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                    
                </div>
                <div class="p-2">

                    <label class="block">Timezone</label>
                    <small>Select your local timezone. This will be used when displaying and submitting reports.</small>
                    
                    <select wire:model="user_timezone">
                        @foreach($timezones as $timezone)
                            <option value="{{ $timezone->name }}">{{ $timezone->name }} ({{ $timezone->offset }})</option>
                        @endforeach 
                    </select>
                    @error('user_timezone') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                    
                </div>

                <div class="p-2">
                    <button class="p-4 w-full bg-blue-400 text-white">Save & login</button>
                </div>
            </div>
        </form>
        <div class="w-full text-center mt-4">
            <a href="{{ route('login') }}" class="rounded-md text-blue-400">Go to login</a>
        </div>
        @endif
    @else
        <form wire:submit.prevent="authorize" class="max-h-screen overflow-y">
            <div class="flex">
                <div class="grow items-justify-center mt-8">
                    <div class="text-2xl text-center">Authorize using the "Services" screen in OHOL</div>
                </div>
                <div class="shrink">
                    <img class="w-60 inline-block" src="{{ asset('assets/uploads/images/authorize-form-character.png') }}" alt="oholcurse-logo" width=64 />
                </div>
            </div>
            <div class="grid grid-rows-1">
                <div class="p-2">
                    <label class="block">Email</label>
                    <small>Copy the email address found in the "Services" on OHOL main menu & paste it here</small>
                    <input wire:model="email" class="mt-2 w-full rounded-md border border-blue-400" type='text' name='email' placeholder="12345678912345678@steamgames.com" />
                    @error('email') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                </div>
                <div class="p-2">
                    <label class="block">Challenge</label>
                    <small>Copy this challenge and paste it into the challenge field on the OHOL services screen</small>
                    <input wire:model="challenge" id="challenge" class="mt-2 w-full rounded-md border border-blue-400 disabled:bg-gray-200" type='text' name='challenge' placeholder="my challenge" disabled />
                    @error('challenge') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                </div>
                <div class="p-2">
                    <label class="block">Hash</label>
                    <small>Paste the calculated hash result from your OHOL "Services" client screen</small>
                    <input wire:model="hash" class="mt-2 w-full rounded-md border border-blue-400" type='text' name='hash' placeholder="3CB83FF21C63D5970A47B5F7E22ADB054EEDF794" />
                    @error('hash') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                </div>

                <div class="p-2">
                    <button class="p-4 w-full bg-blue-400 text-white">Authorize via OHOL</button>
                </div>
                <div class="p-2">
                    @if(isset($this->validationError))
                        <div class="w-full p-2 text-center bg-red-400 text-white">
                            <div>{!! $this->validationError !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
        <div class="w-full text-center mt-4">
            <a href="{{ route('login') }}" class="rounded-md text-blue-400">Go to login</a>
        </div>
    @endif
    <script type="text/javascript">
        
        $(document).ready(function() {
            $("#challenge").click(function(){
                    $("#challenge").select();
                    document.execCommand("copy");
                });
            });
    </script>
</div>
