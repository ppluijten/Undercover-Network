function set_result_loading()
{
    document.getElementById('resultdiv').innerHTML = '<img src="../images/ajax-loader.gif"><span style="margin-left: 5px;">Loading...</span>';
}

function set_type(id)
{
    switch(id) {
        case 0:
            document.getElementById('object_games').style.display = 'none';
            document.getElementById('object_companies').style.display = 'none';
            break;
        case 1:
            document.getElementById('object_games').style.display = '';
            document.getElementById('object_companies').style.display = 'none';
            break;
        case 2:
            document.getElementById('object_games').style.display = 'none';
            document.getElementById('object_companies').style.display = '';
            break;
    }

    document.getElementById('objecttypeid').value = id;
}

function set_sl_type(id)
{
    switch(id) {
        case 0:
            document.getElementById('object_image').style.display = 'none';
            break;
        case 1:
            document.getElementById('object_image').style.display = '';
            break;
        case 2:
            document.getElementById('object_image').style.display = '';
            break;
    }

    document.getElementById('spotlighttypeid').value = id;
}

function set_content_type(id)
{
    if(id == 4) {
        document.getElementById('tr_conclusion').style.display = '';
        document.getElementById('tr_rating').style.display = '';
    } else {
        document.getElementById('tr_conclusion').style.display = 'none';
        document.getElementById('tr_rating').style.display = 'none';
    }
}

function onlyAcceptNumbers(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function onlyAcceptDates(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if(charCode == 45 || charCode == 47)
        return true;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}