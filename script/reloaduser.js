function notifyParent() {
    parent.frameLoaded();
}

function frameLoaded() {
    var iframe = document.getElementById('loginframe');
    iframe.src = 'http://www.undercover-network.nl/forum/forum.php';
    iframe.onload = function(){
       //alert('Logged in succesfuly!');
       location.reload(true);
    };
}

function showLoading() {
    var loginform = document.getElementById('loginform');
    loginform.style.display = 'none';

    var loading = document.getElementById('loading');
    loading.style.display = '';
}