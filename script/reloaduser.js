function notifyParent() {
    parent.frameLoaded();
}

function frameLoaded() {
    //var iframe = document.createElement("iframe");
    //iframe.style.display = 'none';
    //document.body.appendChild(iframe);
    var iframe = document.getElementById("loginframe");
    iframe.src = "reload.php?target=http://www.undercover-network.nl/forum/forum.php&referer="+document.URL;
}

function showLoading() {
    var loginform = document.getElementById('loginform');
    loginform.style.display = 'none';

    var loading = document.getElementById('loading');
    loading.style.display = '';
}