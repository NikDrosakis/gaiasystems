function ajax(file, params,callback,method,content) {
    var xhr;
    if(typeof XMLHttpRequest !== 'undefined') xhr = new XMLHttpRequest();
    else {
        var versions = ["MSXML2.XmlHttp.5.0",
            "MSXML2.XmlHttp.4.0",
            "MSXML2.XmlHttp.3.0",
            "MSXML2.XmlHttp.2.0",
            "Microsoft.XmlHttp"]
        for(var i = 0, len = versions.length; i < len; i++) {
            try {
                xhr = new ActiveXObject(versions[i]);
                break;
            }
            catch(e){}
        }
    }
    xhr.onreadystatechange = ensureReadiness;
    function ensureReadiness(){
        if(xhr.readyState<4){return;}if(xhr.status!==200){return;}
        if(xhr.readyState===4 &&  xhr.status==200){
            var response=content=='json'?JSON.parse(xhr.responseText):xhr.responseText;callback(response);
        }}
    var method = typeof method != 'undefined' ? method : 'GET';
    if(method=="POST"){
        xhr.open("POST", file, true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.send(JSON.stringify(params));
    }else{
        var res=[];for (var i in params) {res.push(i + '=' + params[i])};
        var url=file+'?'+res.join('&');xhr.open("GET", url, true);xhr.send('')
    }
}
self.addEventListener("message", function(e) {
    var args = e.data;
    console.log(args)
    ajax("/index.php?isWorkerRequest=true",args,function(res){
        console.log(res)
        postMessage(res);
    },args.method,args.type);
}, false);