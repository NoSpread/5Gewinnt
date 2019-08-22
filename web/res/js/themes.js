// Wenn das Theme bereits ausgewählt wurde, wird es weiterverwendet.
if (cookie.get('theme') == 'light') ChangeTheme('light');
else ChangeTheme('dark');
dostuff();
function dostuff() {
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
        '<div id="dlacc" class="pl-5 pt-2">Are u sure? <form action="delete_account.php"><input type="submit" class="btn _btn mr-2" value="YES"><input type="button" class="btn _btn" value="NO" onclick="remove_dlac();"></div></form>'
    );
});

function remove_dlac() {
    $('#dlacc').remove();
    return;
}

var inside_element = false;
$('.game-hover-overlay-wrapper > .row > .col')
    .mouseenter(function() {
        inside_element = true;
        $(this)
            .append('<div class="coin1 preselect"></div>')
            .animate();
        animate_coin();
    })
    .mouseleave(function() {
        inside_element = false;
        $('.game-hover-overlay-wrapper > .row > .col > .preselect').remove();
    });

function animate_coin() {
    $('.preselect').animate(
        {
            top: '+=20px'
        },
        'slow'
    );
    $('.preselect').animate(
        {
            top: '-=20px'
        },
        'slow',
        function() {
            if (inside_element == true) animate_coin();
        }
    );
}

$('#blacknwhite').click(function() {
    if ($('#blacknwhite').hasClass('disabled')) return;

    if ($('#blacknwhite > div').hasClass('black')) {
        $('#blacknwhite > div')
            .removeClass('black')
            .addClass('white');
        $('#blacknwhite > span').text('White');
    } else if ($('#blacknwhite > div').hasClass('white')) {
        $('#blacknwhite > div')
            .removeClass('white')
            .addClass('black');
        $('#blacknwhite > span').text('Black');
    }
});

function gamestuff() {
    setTimeout(function() {
        $('.game-overlay').fadeOut(1000);
    }, 1000);
    return;
}

function end_screen(params) {
    $('.skewed-header')
        .text(params)
        .append(
            '<div><form action="index.php"><input type="submit" class="btn _btn btn-lg" value="back to lobby"></form></div>'
        );

    $('.game-overlay').fadeIn(1000);
    return;
}

$('#resignButton').click(function() {
    $('#column3').append(
        '<div id="resign" class="pt-2">Are u sure? <input type="button" class="btn _btn mr-2" value="YES" onclick="resign();"><input type="button" class="btn _btn" value="NO" onclick="remove_resign();"></div>'
    );
});

function remove_resign() {
    $('#resign').remove();
}
