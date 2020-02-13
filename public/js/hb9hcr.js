jQuery(document).ready(function ($) {
    $('.success, .error').each(function () {
        let self = this;
        setTimeout(function () {
            $(self).removeClass('success error');
        }, 500);
    });
});