jQuery(document).ready(function ($) {
    $('.success, .error').each(function () {
        let self = this;
        setTimeout(function () {
            $(self).removeClass('success error');
        }, 500);
    });

    let gpsl = $('.gpsl');

    let poll = function () {
        setTimeout(function () {
            $.ajax({
                url: "/logger/ajax", success: function (data) {
                    update(data);
                }, dataType: "json", complete: poll, timeout: 30000
            });
        }, 500);
    };

    let update = function (data) {
        $.each(data, function (k, v) {
            if ('gpsd' === k || 'gpsl' === k) {
                let i = $('[data-name=' + k + ']');
                '1' === v ? i.addClass('a') : i.removeClass('a');
                return;
            }
            $('[data-name=' + k + ']', gpsl).html(v);
        });
    };

    if (gpsl.length) poll();
});