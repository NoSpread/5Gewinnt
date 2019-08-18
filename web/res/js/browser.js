var isIE = false || !!document.documentMode;
var isEdge = !isIE && !!window.StyleMedia;

if (isIE || isEdge) {
    $('body').empty();
    $('body').append(
        '<div class="w-100 text-center">sorry your browser is not supported <div class="mt-5"><button id="chrome" class="mr-5 btn btn-lg _btn w-25">Download Chrome<br><img width="200" height="200" src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Google_Chrome_icon_%28September_2014%29.svg/1200px-Google_Chrome_icon_%28September_2014%29.svg.png"></button><button id="firefox" class="btn btn-lg _btn w-25">Download Firefox<br><img width="200" height="200" src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Firefox_Logo%2C_2017.svg/1200px-Firefox_Logo%2C_2017.svg.png"></button></div></div>'
    );
}

$('#chrome').click(function() {
    window.open('https://www.google.de/chrome/');
});

$('#firefox').click(function() {
    window.open('https://www.mozilla.org/en-US/firefox/new/');
});
