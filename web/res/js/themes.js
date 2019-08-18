dostuff();
function dostuff() {
    // Wenn das Theme bereits ausgewählt wurde, wird es weiterverwendet.
    if (cookie.get('theme') == 'light') ChangeTheme('light');
    else ChangeTheme('dark');
    setTimeout(function() {
        $('.spinner').fadeOut(1000);
        setTimeout(function() {
            $('.loader').remove();
            $('.skewed-top').animate({ top: '-100%' });
            $('.skewed-bottom').animate({ bottom: '-100%' });
            setTimeout(function() {
                $('.themes').fadeIn(1000);
            }, 500);
        }, 1000);
    }, 500);
    return;
}

function ChangeTheme(theme) {
    // Wechsel des Dark-Themes in das Light-Theme und vice versa
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

// Der Knopf zum Theme-Wechsel wird gedrückt -> Theme bleibt erhalten
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
    $('#profile').fadeIn(1000);
});

$('#sidebar-settings').click(function() {
    $('#settings').fadeIn(1000);
});

$('.menu-close > button').click(function() {
    $('.menu-overlay').fadeOut(1000);
});

$('#dlac').click(function() {
    $('#settings .menu').append(
        '<div id="dlacc" class="pl-5 pt-2">Are u sure? <form action="delete_account.php"><input type="submit" class="btn _btn mr-2" value="YES"></input><input type="button" class="btn _btn" value="NO" onclick="eugjoerg();"></input></div></form>'
    );
});

function eugjoerg() {
    $('#dlacc').remove();
    return;
}
