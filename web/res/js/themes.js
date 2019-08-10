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
            }, 500);
        }, 1000);
    }, 500);
}

function ChangeTheme(theme) {
    switch (theme) {
        case 'light':
            $('.themes > button')
                .removeClass('mdi-weather-night')
                .addClass('mdi-weather-sunny');
            $('#theme').attr('href', '../res/css/light.css');
            break;

        case 'dark':
            $('.themes > button')
                .removeClass('mdi-weather-sunny')
                .addClass('mdi-weather-night');
            $('#theme').attr('href', '../res/css/dark.css');
            break;
    }
}

$('.themes > button').click(function() {
    if ($('.themes > button').hasClass('mdi-weather-sunny')) {
        ChangeTheme('dark');
        cookie.set('theme', 'dark', 'never', '/');
    } else {
        ChangeTheme('light');
        cookie.set('theme', 'light', 'never', '/');
    }
});

$('.sidebar-btn > button').click(function() {
    if (!$('.sidebar').hasClass('active')) {
        $('.sidebar').addClass('active');
        $('.sidebar-btn > button')
            .removeClass('mdi-chevron-right')
            .addClass('mdi-chevron-left');
    } else {
        $('.sidebar').removeClass('active');
        $('.sidebar-btn > button')
            .removeClass('mdi-chevron-left')
            .addClass('mdi-chevron-right');
    }
});

$('#sidebar-profile').click(function() {
    $('.menu-overlay').fadeIn(1000);
});

$('.menu-close > button').click(function() {
    $('.menu-overlay').fadeOut(1000);
});
