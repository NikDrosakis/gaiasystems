function ajax(file, params, callback, method, responseFormat) {
    var xhr;
    if (typeof XMLHttpRequest !== 'undefined') xhr = new XMLHttpRequest();
    else {
        var versions = [
            "MSXML2.XmlHttp.5.0",
            "MSXML2.XmlHttp.4.0",
            "MSXML2.XmlHttp.3.0",
            "MSXML2.XmlHttp.2.0",
            "Microsoft.XmlHttp"
        ];
        for (var i = 0, len = versions.length; i < len; i++) {
            try {
                xhr = new ActiveXObject(versions[i]);
                break;
            } catch (e) {}
        }
    }

    xhr.onreadystatechange = ensureReadiness;

    function ensureReadiness() {
        if (xhr.readyState < 4) {
            return;
        }

        if (xhr.status === 404) {
            callback({ error: true, message: `File not found: ${file}` });
            return;
        }

        if (xhr.readyState === 4 && xhr.status >= 200 && xhr.status < 300) {
            var response = responseFormat === 'json' ? JSON.parse(xhr.responseText) : xhr.responseText;
            callback(response);
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            callback({ error: true, message: `HTTP error: ${xhr.status}` });
        }
    }

    method = typeof method !== 'undefined' ? method : 'GET';

    if (method === "POST") {
        xhr.open("POST", file, true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.send(JSON.stringify(params));
    } else {
        var res = [];
        for (var i in params) {
            res.push(encodeURIComponent(i) + '=' + encodeURIComponent(params[i]));
        }
        var url = file + '?' + res.join('&');
        xhr.open("GET", url, true);
        xhr.send('');
    }
}

self.addEventListener("message", function(e) {
    var args = e.data;
    //var endpoint = args.file || "/admin/buffer_xhr.php"; // Default to /admin/buffer_xhr.php if no endpoint is provided
    var method = args.method || "GET"; // Default to GET if no method is provided
    var contentType = args.type || "json"; // Default to JSON if no content type is provided

    ajax("/admin/index.php?isWorkerRequest=true", args.params, function(res) {
        postMessage(res);
    }, method, contentType);
}, false);
