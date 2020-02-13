jQuery(document).ready(function ($) {
    $('.success, .error').each(function () {
        let self = this;
        setTimeout(function () {
            $(self).removeClass('success error');
        }, 500);
    });

    let gpsl = $('.gpsl');

    if (gpsl.length) {
        let conn = new WebSocket('ws://localhost:8888');
        conn.onerror = function (e) {
            console.log(e);
            update({
                'lat': 47.5,
                'lon': 8.75,
                'course_m': 347,
                'course_t': 350,
                'speed_m': 87,
                'alt': 441,
                'hdop': 0.7,
            });
        };

        conn.onmessage = function (e) {
            update(e.data);
        };

        let update = function (e) {
            $.each(e, function (k, v) {
                $('[data-name=' + k + ']', gpsl).html(v);
            });
        }
    }
});