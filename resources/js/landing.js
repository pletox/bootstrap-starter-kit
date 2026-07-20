import 'bootstrap';

$(function () {
    const $countdown = $('[data-countdown]');

    if (!$countdown.length) {
        return;
    }

    const targetTime = new Date($countdown.data('countdown')).getTime();

    const refreshCountdown = () => {
        const remaining = Math.max(0, targetTime - Date.now());
        const units = {
            days: Math.floor(remaining / 86400000),
            hours: Math.floor((remaining / 3600000) % 24),
            minutes: Math.floor((remaining / 60000) % 60),
            seconds: Math.floor((remaining / 1000) % 60),
        };

        $.each(units, (unit, value) => {
            $countdown.find(`[data-countdown-unit="${unit}"]`).text(String(value).padStart(unit === 'days' ? 3 : 2, '0'));
        });
    };

    refreshCountdown();
    window.setInterval(refreshCountdown, 1000);

    $('.ks-header .nav-link').on('click', function () {
        $('.ks-header .navbar-collapse').collapse('hide');
    });
});
