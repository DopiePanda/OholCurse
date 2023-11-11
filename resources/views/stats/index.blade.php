<x-app-layout>
    <x-slot name="header">
        <h2 class="text-4xl text-gray-800 leading-tight break-all text-center dark:text-gray-200">
            Statistics
        </h2>

    </x-slot>
    <div class="py-1">

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1>{{ $chart1->options['chart_title'] }}</h1>
            {!! $chart1->renderHtml() !!}
        </div>
    </div>

    @section('before-body-end')
        {!! $chart1->renderChartJsLibrary() !!}
        {!! $chart1->renderJs() !!}
    @endsection

</x-app-layout>