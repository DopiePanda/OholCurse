<div class="dark:bg-slate-400 p-4">
    <div class="grid grid-cols-2">
        <div>
            <img class="mx-auto h-90" src="{{ asset($this->sprite) }}" />
        </div>
        <div class="mt-8">
            <table class="dark:text-gray-800">
                <tr>
                    <td class="py-1 border-b">Name: {{ ucwords(strtolower($life->name->name), ' ') }}</td>
                </tr>
                <tr>
                    <td class="py-1 border-b capitalize">Race: {{ $life->family_type }}</td>
                </tr>
                <tr>
                    <td class="py-1 border-b">Age of death: {{ $death->age }}</td>
                </tr>
                <tr>
                    <td class="py-1 border-b">Leaderboard records set: {{ $records }}</td>
                </tr>
                <tr>
                    @if($object->object)
                        <td class="py-1 border-b">Favorite object: {{ explode('#', $object->object->name)[0] ?? 'Test' }}</td>
                    @else
                        <td class="py-1 border-b">Favorite object: Unknown {{ $object }} </td>
                    @endif
                </tr>
                <tr>
                    <td class="py-1 border-b">Favorite food: <span class="italic">coming soon</span></td>
                </tr>
                <tr>
                    <td class="py-1 border-b">Estimated Yum-bonus: <span class="italic">coming soon</span></td>
                </tr>
                <tr>
                    <td class="py-1 border-b">Curses sent: {{ $curses }}</td>
                </tr>
                <tr>
                    <td class="py-1 border-b">Trusts sent: {{ $trusts }}</td>
                </tr>
                <tr>
                    <td class="py-1 border-b">Forgives sent: {{ $forgives }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
