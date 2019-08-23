// Wenn das Theme bereits ausgewählt wurde, wird es weiterverwendet.
if (cookie.get('theme') == 'light') change_theme('light');
else change_theme('dark');
animate_loader();
function animate_loader() {
    setTimeout(function() {
        $('.spinner').fadeOut(1000);
        setTimeout(function() {
            $('.loader').remove();
            $('.skewed-top').animate({ top: '-100%' });
            $('.skewed-bottom').animate({ bottom: '-100%' });
            setTimeout(function() {
                layout_fix();
                $('.themes').fadeIn(1000);
            }, 500);
        }, 1000);
    }, 500);
    return;
}

function layout_fix() {
    $('.sidebar').css({ 'z-index': '50' });
    $('.sidebar-btn').css({ 'z-index': '50' });
    $('.menu-overlay').css({ 'z-index': '1337' });
    $('.menu-close').css({ 'z-index': '1338' });
    $('.game-overlay').css({ 'z-index': '51' });
    return;
}

function change_theme(theme) {
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
    return;
}

// Der Knopf zum Theme-Wechsel wird gedrückt -> Theme bleibt erhalten
$('.themes > button').click(function() {
    if ($('.themes > button').hasClass('mdi-weather-sunny')) {
        change_theme('dark');
        cookie.set('theme', 'dark', 'never', '/');
    } else {
        change_theme('light');
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
    if ($('#dlacc').length) return;

    $('#settings .menu').append(
        '<div id="dlacc" class="pl-5 pt-2">Are u sure? <form action="delete_account.php"><input type="submit" class="btn _btn mr-2" value="YES"><input type="button" class="btn _btn" value="NO" onclick="remove_dlac();"></div></form>'
    );
});

function remove_dlac() {
    $('#dlacc').remove();
    return;
}

function remove_game_overlay() {
    setTimeout(function() {
        $('.game-overlay').fadeOut(1000);
    }, 1000);
    return;
}

function end_screen(params) {
    if ($('#btl').length) return;

    $('.skewed-header')
        .text(params)
        .append(
            '<div><form action="index.php"><input id="btl" type="submit" class="btn _btn btn-lg" value="back to lobby"></form></div>'
        );

    $('.game-overlay').fadeIn(1000);
    return;
}

$('#resignButton').click(function() {
    if ($('#resign').length) return;

    $('#column3').append(
        '<div id="resign" class="pt-2">Are u sure? <input type="button" class="btn _btn mr-2" value="YES" onclick="resign();"><input type="button" class="btn _btn" value="NO" onclick="remove_resign();"></div>'
    );
});

function remove_resign() {
    $('#resign').remove();
    return;
}

function create_clocks(mode) {
    switch (mode) {
        case 'spectator':
            $('#column1').append(
                '<div class="form-label-group text-center mb-5"><div id="clock1title" class="pt-2">LOADING</div><div id="clock1" class="t-48px">LOADING</div></div><div class="form-label-group text-center mb-5"><div id="clock2title" class="pt-2">LOADING</div><div id="clock2" class="t-48px">LOADING</div></div><div class="text-center mt-5"><div id="stateMessage" class="p-2">Loading . . .</div></div>'
            );
            $('.game-bg').css({ 'min-height': 595 });
            break;

        case 'player':
            $('#column1').append(
                '<div class="form-label-group text-center"><div id="clock1title" class="pt-2">LOADING</div><div id="clock1" class="t-48px">LOADING</div></div class="mb-5"><div id="clock2">Loading . . .</div><div class="text-center mt-5"><div id="stateMessage" class="p-2">Loading . . .</div></div>'
            );
            break;
    }
}
