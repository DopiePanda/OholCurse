<div class="p-4 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border border-skin-base dark:border-skin-base-dark" wire:ignore.self>
    @if($validated == true)
        @if($emailInUse == false)
            <h2 class="text-skin-base dark:text-skin-base-dark">Create your account login</h2>
            
            <form wire:submit.prevent='createUser'>
                @csrf
                <div class="grid grid-rows-1">
                    <div class="p-2">
                        <label class="block text-skin-base dark:text-skin-base-dark">Username</label>
                        <input wire:model="username" class="mt-2 w-full rounded-md border border-blue-400 dark:bg-slate-700 dark:text-gray-200 dark:placeholder:text-gray-500 dark:border-gray-600 dark:focus:bg-slate-600" type='text' name='email' placeholder="MyFancyUsername" />
                        @error('username') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                        <small>Enter a username for future login.</small>
                    </div>
                    <div class="p-2">
                        <label class="block text-skin-base dark:text-skin-base-dark">Password</label>
                        <input wire:model="password" class="mt-2 w-full rounded-md border border-blue-400 dark:bg-slate-700 dark:text-gray-200 dark:placeholder:text-gray-500 dark:border-gray-600 dark:focus:bg-slate-600" type='password' name='challenge' placeholder="**********" />
                        @error('password') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                        <small>Enter a secure password. Please use one not used on different services</small>
                    </div>
                    <div class="p-2">
                        <label class="block text-skin-base dark:text-skin-base-dark">Timezone</label>
                        <select wire:model="user_timezone" class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-slate-700 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600">
                            @foreach($timezones as $timezone)
                                <option value="{{ $timezone->name }}">{{ $timezone->name }} ({{ $timezone->offset }})</option>
                            @endforeach 
                        </select>
                        @error('user_timezone') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                        <small class="text-skin-muted dark:text-skin-muted-dark">Select your local timezone for displaying and submitting reports. This can be changed later from your profile.</small>
                    </div>

                    <div class="p-2">
                        <button type="submit" class="p-4 w-full text-white rounded-lg bg-skin-fill dark:bg-skin-fill-dark">Create new user account</button>
                    </div>
                </div>
            </form>
        @elseif($emailInUse == true)
            <h2 class="text-2xl text-center text-skin-base dark:text-skin-base-dark">Select a new password for you account</h2>
            <form wire:submit.prevent='updateUser'>
                <div class="grid grid-rows-1">
                    <div class="p-2">

                        <label class="block text-skin-base dark:text-skin-base-dark">Username</label>
                        <small class="block text-skin-muted dark:text-skin-muted-dark">Previously selected username</small>

                        <input wire:model="username" class="mt-2 w-full rounded-md border border-blue-400 disabled:bg-gray-200 dark:disabled:bg-slate-900 dark:disabled:text-gray-500 dark:bg-slate-700 dark:text-gray-200 dark:placeholder:text-gray-500 dark:border-gray-600 dark:focus:bg-slate-600" type='text' name='username' placeholder="MyUsername" disabled />
                        @error('username') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                        
                    </div>
                    <div class="p-2">

                        <label class="block text-skin-base dark:text-skin-base-dark">Password</label>
                        <small class="block text-skin-muted dark:text-skin-muted-dark">Enter new secure password or leave as is to keep current password.</small>

                        <input wire:model="password" class="mt-2 w-full rounded-md border border-blue-400 dark:bg-slate-700 dark:text-gray-200 dark:placeholder:text-gray-500 dark:border-gray-600 dark:focus:bg-slate-600" type='password' name='password' placeholder="************" />
                        @error('password') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                        
                    </div>
                    <div class="p-2">

                        <label class="block text-skin-base dark:text-skin-base-dark">Timezone</label>
                        <small class="block text-skin-muted dark:text-skin-muted-dark">Select your local timezone. This will be used when displaying and submitting reports.</small>
                        
                        <select wire:model="user_timezone" class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-slate-700 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600">
                            @foreach($timezones as $timezone)
                                <option value="{{ $timezone->name }}">{{ $timezone->name }} ({{ $timezone->offset }})</option>
                            @endforeach 
                        </select>
                        @error('user_timezone') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                        
                    </div>

                    <div class="p-2">
                        <button class="p-4 w-full text-white rounded-lg bg-skin-fill dark:bg-skin-fill-dark">Save & login</button>
                    </div>
                </div>
            </form>
            <div class="w-2/3 mx-auto mt-2 border border-b border-gray-700"></div>
            <div class="w-full text-center mt-4">
                <a href="{{ route('login') }}" class="rounded-md text-skin-base dark:text-skin-base-dark">Go to login</a>
            </div>
        @endif
    @else
        <form wire:submit.prevent="authorizeUser" class="max-h-screen overflow-y">
            <div class="flex">
                <div class="grow items-justify-center mt-8">
                    <div class="text-2xl text-center text-skin-base dark:text-skin-base-dark">Authorize using the "Services" screen in OHOL</div>
                </div>
                <div class="shrink">
                    <img class="w-60 inline-block" src="{{ asset('assets/uploads/images/authorize-form-character.png') }}" alt="oholcurse-logo" width=64 />
                </div>
            </div>
            <div class="grid grid-rows-1">
                <div class="p-2">
                    <label class="block text-skin-base dark:text-skin-base-dark">Email</label>
                    <small class="block text-skin-muted dark:text-skin-muted-dark">Copy the email address found in the "Services" on OHOL main menu & paste it here</small>
                    <input wire:model="email" class="mt-3 w-full rounded-md border border-blue-400 dark:bg-slate-700 dark:text-gray-200 dark:placeholder:text-gray-500 dark:border-gray-600 dark:focus:bg-slate-600" type='text' name='email' placeholder="Ex: 12345678912345678@steamgames.com" />
                    @error('email') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                </div>
                <div class="p-2">
                    <label class="block text-skin-base dark:text-skin-base-dark">Challenge</label>
                    <small class="block text-skin-muted dark:text-skin-muted-dark">Copy this challenge and paste it into the challenge field on the OHOL services screen</small>
                    <input wire:model="challenge" id="challenge" class="mt-2 w-full rounded-md text-skin-muted dark:text-skin-muted-dark border border-blue-400 disabled:bg-gray-200 dark:disabled:bg-slate-900 dark:disabled:text-white dark:focus:bg-slate-600" type='text' name='challenge' placeholder="my challenge" disabled />
                    @error('challenge') <div class="mt-3 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                </div>
                <div class="p-2">
                    <label class="block text-skin-base dark:text-skin-base-dark">Hash</label>
                    <small class="block text-skin-muted dark:text-skin-muted-dark">Paste the calculated hash result from your OHOL "Services" client screen</small>
                    <input wire:model="hash" class="mt-3 w-full rounded-md border border-blue-400 dark:bg-slate-700 dark:text-gray-200 dark:placeholder:text-gray-500 dark:border-gray-600 dark:focus:bg-slate-600" type='text' name='hash' placeholder="Ex: 3CB83FF21C63D5970A47B5F7E22ADB054EEDF794" />
                    @error('hash') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                </div>

                <div class="p-2">
                    <button class="mt-4 p-4 w-full text-white rounded-lg bg-skin-fill dark:bg-skin-fill-dark">Authorize via OHOL</button>
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
        <div class="w-2/3 mx-auto mt-2 border border-b border-gray-700"></div>
        <div class="w-full text-center mt-4">
            <a href="{{ route('login') }}" class="rounded-md text-skin-base dark:text-skin-base-dark">Go to login</a>
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
