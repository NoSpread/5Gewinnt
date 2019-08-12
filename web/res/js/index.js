var cookie = {
    set: function(name, value, expire, path) {
        // Informationen werden im Browser gespeichert
        return (document.cookie =
            name + '=' + value + ';expire=' + expire + ';path=' + path + ';');
    },
    get: function(name) {
        // Informationen werden aus dem Browser gelesen
        var data = document.cookie.replace(/\s/g, '').split(';');
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
