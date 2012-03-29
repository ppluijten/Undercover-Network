function notifyParent() {
    parent.frameLoaded();
}

function frameLoaded() {
    var iframe = document.getElementById('loginframe');
    iframe.src = 'http://www.undercover-network.nl/forum/forum.php';
    setTimeout('location.reload(true);', 500);
}