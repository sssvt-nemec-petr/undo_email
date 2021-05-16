rcmail.addEventListener('init', function(evt) {
    $(".formbuttons").append("<a id='testButton' style='position:relative;top: 1px;padding:10px;border-radius: 4px;border:1px solid rgb(55,190,255);background-color: rgb(0,106,157);color: rgb(226,232,233);z-index: 9999' href='javascript:void(0)'>Undo last</a>");
    $(".formbuttons").append("<a id='basedButton' style='position:relative;left: 11px;top: 1px;padding:10px;border-radius: 4px;border:1px solid rgb(55,190,255);background-color: rgb(0,106,157);color: rgb(226,232,233);z-index: 9999' href='javascript:void(0)'>Send last</a>");
    $("#testButton").hide();
    $("#basedButton").hide();
    $("#messagestack").css("opacity","0");
    // rcmail.register_button('plugin.cancelMail','testing','link');
    // rcmail.register_command('plugin.cancelMail',function (resp){
    //     alert("Callback")
    // }, true);
    // rcmail.enable_command('plugin.cancelMail', true);

    rcmail.addEventListener('plugin.callback', function (resp){
        alert("Massage arrived sir");
        console.log(resp);
    })



    $("#rcmbtn112").click(function(){
        $("#testButton").show();
        $("#basedButton").show();
        $(window).bind('beforeunload', function(){
            var objtext = rcmail.http_get('plugin.sendMail');
        });
        var countDownDate = new Date().getTime()+8*1000;

        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (distance < 0) {
                clearInterval(x);
                var objtext = rcmail.http_get('plugin.sendMail');
                alert('Email sent');
                window.history.back();
            }
        }, 1000);
    })
    $("#testButton").click(function (){
        var obj = rcmail.http_get('plugin.cancelMail');
        window.history.back();
        console.log(obj);
    });
    $("#basedButton").click(function (){
        var obj = rcmail.http_get('plugin.sendMail');
        window.history.back();
        console.log(obj);
    });
});