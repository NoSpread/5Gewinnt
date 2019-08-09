dostuff();
function dostuff() {
    if (cookie.get('theme') == 'light') ChangeTheme('light');
    else ChangeTheme('dark');
    setTimeout(function() {
        $('.spinner').fadeOut(1000);
        setTimeout(function() {
            $('.loader').remove();
            $('.skewed-top').animate({ top: '-95%' });
            $('.skewed-bottom').animate({ bottom: '-95%' });
            setTimeout(function() {
                $('.themes').fadeIn(1000);
                $('.sidebar').fadeIn(1000);
            }, 500);
        }, 1000);
    }, 500);
}

function ChangeTheme(theme) {
    switch (theme) {
        case 'light':
            $('.btn-theme')
                .removeClass('mdi-weather-night')
                .addClass('mdi-weather-sunny');
            $('#theme').attr('href', '../res/css/light.css');
            break;

        case 'dark':
            $('.btn-theme')
                .removeClass('mdi-weather-sunny')
                .addClass('mdi-weather-night');
            $('#theme').attr('href', '../res/css/dark.css');
            break;
    }
}

$('.btn-theme').click(function() {
    if ($('.btn-theme').hasClass('mdi-weather-sunny')) {
        ChangeTheme('dark');
        cookie.set('theme', 'dark', 'never', '/');
    } else {
        ChangeTheme('light');
        cookie.set('theme', 'light', 'never', '/');
    }
});

$('.sidebar-_btn').click(function() {
    if (!$('.sidebar').hasClass('active')) {
        $('.sidebar').addClass('active');
        $('.sidebar-_btn')
            .removeClass('mdi-chevron-right')
            .addClass('mdi-chevron-left');
    } else {
        $('.sidebar').removeClass('active');
        $('.sidebar-_btn')
            .removeClass('mdi-chevron-left')
            .addClass('mdi-chevron-right');
    }
});
