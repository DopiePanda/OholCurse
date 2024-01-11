import './bootstrap';
import '../../vendor/masmerise/livewire-toaster/resources/js';

$(document).ready(function () {
    $('#gameObjects').select2({
        width: "style"
    });
    $('#gameObjects').on('change', function (e) {
        var data = $('#gameObjects').select2("val");
        $wire.dispatch('object-changed', { object: data });
    });
});