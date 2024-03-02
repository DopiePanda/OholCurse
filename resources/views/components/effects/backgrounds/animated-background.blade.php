<div class="w-full h-full -z-10 fixed overflow-visible top-0 left-0">
    @if($donator != null && request()->routeIs('player.*'))

        <!-- Override local theme with donators chosen theme -->
        @if(auth()->user())
            <script type="text/javascript">
                document.body.classList.remove('{{ auth()->user()->theme }}');
                document.body.classList.add("{{ $donator->theme }}");
            </script>
        @else
            <script type="text/javascript">
                document.body.classList.add("{{ $donator->theme }}");
            </script>
        @endif
        
        <!-- Set custom donator background --> 

        @if($donator->background == 'squares')
            @include('effects.backgrounds.squares')
        @endif

        @if($donator->background == 'gradient')
            @include('effects.backgrounds.gradient-1')
        @endif

        @if($donator->background == 'gradient-2')
            @include('effects.backgrounds.gradient-2')
        @endif

        @if($donator->background == 'hearts')
            @include('effects.backgrounds.hearts')
        @endif

        @if($donator->background == 'dots')
            @include('effects.backgrounds.dots')
        @endif

        @if($donator->background == 'goobs')
            @include('effects.backgrounds.goobs')
        @endif

        @if($donator->background == 'girly')
            @include('effects.backgrounds.girly')
        @endif

        @if($donator->background == 'diagonals')
            @include('effects.backgrounds.diagonals')
        @endif

    @endif
</div>