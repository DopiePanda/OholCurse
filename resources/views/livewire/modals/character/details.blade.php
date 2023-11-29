<div>
    <div class="grid grid-cols-2">
        <div>
            <img class="mx-auto h-90" src="{{ asset($this->sprite) }}" />
        </div>
        <div class="mt-8">
            <table>
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
                    <td class="py-1 border-b">Curses sent: {{ $curses }}</td>
                </tr>
                <tr>
                    <td class="py-1 border-b">Trusts sent: {{ $trusts }}</td>
                </tr>
                <tr>
                    <td class="py-1 border-b">Forgives sent: {{ $forgives }}</td>
                </tr>
                <tr>
                    <td class="py-1 border-b">Leaderboard records set: {{ $records }}</td>
                </tr>
                <tr>
                    @if($object->object)
                        <td class="py-1 border-b">Favorite object: {{ explode('#', $object->object->name)[0] ?? 'Unknown' }}</td>
                    @else
                        <td class="py-1 border-b">Favorite object: Unknown</td>
                    @endif
                </tr>
                <tr>
                    <td class="py-1 border-b">Favorite food: <span class="italic">coming soon</span></td>
                </tr>
                <tr>
                    <td class="py-1 border-b">Estimated Yumbonus: <span class="italic">coming soon</span></td>
                </tr>
            </table>
        </div>
    </div>
</div>
