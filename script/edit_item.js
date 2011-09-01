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
    document.getElementById('spotlighttypeid').value = id;
}