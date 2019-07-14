dostuff();

function dostuff() {
    setTimeout(() => {
        $('.spinner').fadeOut(1000);
        setTimeout(() => {
            $('.loader').remove();
            $('.skewed-top').animate({ top: '-95%' });
            $('.skewed-bottom').animate({ bottom: '-95%' });
            setTimeout(() => {
                $('.themes').fadeIn(1000);
            }, 500);
        }, 1000);
    }, 500);
}

function ChangeTheme(theme) {
    switch (theme) {
        case 'light':
            $('.btn-theme')
                .removeClass('mdi-weather-sunny')
                .addClass('mdi-weather-night');
            break;

        case 'dark':
            $('.btn-theme')
                .removeClass('mdi-weather-night')
                .addClass('mdi-weather-sunny');
            break;
    }
}

$('.btn-theme').click(function() {
    if ($('.btn-theme').hasClass('mdi-weather-sunny')) ChangeTheme('light');
    else ChangeTheme('dark');
});
