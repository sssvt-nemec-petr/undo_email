rcmail.addEventListener('init', function(evt) {
    $("body").append("<a id='testButton' style='position:absolute;left:250px;top:942.5px;z-index: 9999' href='javascript:void(0)'>UNDO LAST</a>");

    // rcmail.register_button('plugin.cancelMail','testing','link');
    // rcmail.register_command('plugin.cancelMail',function (resp){
    //     alert("Callback")
    // }, true);
    // rcmail.enable_command('plugin.cancelMail', true);

    rcmail.addEventListener('plugin.callback', function (resp){
        alert("Massage arrived sir");
        console.log(resp);
    })

    $("#testing").click(function (){
        var obj = rcmail.http_get('plugin.cancelMail');
        // var obj = rcmail.command('rcmail.cancelMail');
        console.log(obj);
    });
});