<div class="dark:bg-slate-400 p-4">
    <div class="text-center text-2xl">{{ $name['first'] }} {{ $name['last'] }}</div>
    <div class="mt-2 grid grid-cols-2">
        <div>
            <img class="mx-auto h-96" src="{{ asset($this->sprite) }}" />
        </div>
        <div class="mt-2">
            @if($life->parent_id == null)
                @if($life->pos_x > -190000000)
                    <div class="py-1">
                        {{ $name['first'] }} {{ $name['last'] }} was the founder and creator of the {{ $name['last'] }} family,
                        born in the <span class="font-semibold">{{ ucfirst($life->family_type) }} biome</span>
                    </div>
                @else
                    <div class="py-1">
                        @if ($name['first'] == 'UNNAMED' && $name['last'] == 'UNNAMED')
                            This unknown person was born in Donkey Town in the <span class="font-semibold">{{ ucfirst($life->family_type) }} biome</span>
                        @else
                            {{ $name['first'] }} {{ $name['last'] }} was born in Donkey Town in the <span class="font-semibold">{{ ucfirst($life->family_type) }} biome</span>
                        @endif
                    </div>
                @endif
            @else
                <div class="py-1">
                    {{ $name['first'] }} was a member of the {{ $name['last'] }} family,
                    born in the <span class="font-semibold">{{ ucfirst($life->family_type) }} biome</span> 
                    by {{ $pronoun[1] }} mother <span class="font-semibold">
                        <a href="{{ route('player.curses', $life->parent->player_hash) }}" class="text-blue-400 dark:text-red-600" tabindex="-1">
                            {{ ucwords(strtolower($life->parent->name->name), ' ') }}
                        </a></span>
                </div>
            @endif
            <div class="py-1">
                {{ $name['first'] }} was <span class="font-semibold">{{ $activity }} active</span> with {{ $pronoun[1] }} <span class="font-semibold">{{ $actions }} actions</span> in and around the town.
            </div>
            @if($object)
                <div class="py-1">
                    {{ ucfirst($pronoun[1]) }} favorite object was the <span class="font-semibold">{{ explode('#', $object->object->name)[0] }}</span> which {{ $pronoun[0] }} interacted with <span class="font-semibold">{{ $object->amount }} times</span>.
                </div>
            @endif
            <div class="py-1">
                {{ ucfirst($pronoun[0]) }} made {{ $trusts }} new friends and {{ $curses }} new enemies.
            </div>
            @if($records > 0)
                <div class="py-1">
                    During {{ $pronoun[1] }} lifetime {{ $pronoun[0] }} set {{ $records }} new records. 
                </div>
            @endif
            <div class="py-1">
                {{ ucfirst($pronoun[0]) }} died at an age of {{ floor($death->age) }} to {{ $death->died_to }}
            </div>
        </div>
    </div>
</div>
