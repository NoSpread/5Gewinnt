var cookie = {
    set: function(name, value, expire, path) {
        return (document.cookie =
            name + '=' + value + ';expire=' + expire + ';path=' + path + ';');
    },
    get: function(name) {
        var data = document.cookie.split(';');
        var cookies = {};
        for (var i = 0; i < data.length; i++) {
            var erjg93 = data[i].split('=');
            cookies[erjg93[0]] = erjg93[1];
        }
        if (name) {
            return cookies[name] || null;
        } else {
            return cookies;
        }
    }
};
