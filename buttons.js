rcmail.addEventListener('init', function(evt) {
    $(".formbuttons").append("<a id='undoButton' style='position:relative;top: 1px;padding:10px;border-radius: 4px;border:1px solid rgb(55,190,255);background-color: rgb(0,106,157);color: rgb(226,232,233);z-index: 9999' href='javascript:void(0)'>Undo</a>");
    $(".formbuttons").append("<a id='sendButton' style='position:relative;left: 11px;top: 1px;padding:10px;border-radius: 4px;border:1px solid rgb(55,190,255);background-color: rgb(0,106,157);color: rgb(226,232,233);z-index: 9999' href='javascript:void(0)'>Send now</a>");
    $("#undoButton").hide();
    $("#sendButton").hide();
    $("#messagestack").css("opacity","0");
    var enableSend = true;

    rcmail.addEventListener('plugin.callback', function (resp){
        alert("Massage arrived sir");
        console.log(resp);
    })
    $("#rcmbtn112").click(function(){
        window.onbeforeunload = null;
        enableSend = true;
        $("#undoButton").show();
        $("#sendButton").show();
        $(window).bind('beforeunload', function(){
            if(enableSend == true) {
                var objtext = rcmail.http_get('plugin.sendMail');
            }
        });
        var countDownDate = new Date().getTime()+8*1000;

        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (distance < 0) {
                clearInterval(x);
                if(enableSend == true) {
                    var objtext = rcmail.http_get('plugin.sendMail');
                    alert('Email sent');
                    window.history.back();
                }
                enableSend = false;
            }
        }, 1000);
    })
    $("#undoButton").click(function (){
        var obj = rcmail.http_get('plugin.cancelMail');
        enableSend = false;
        console.log(obj);
        $("#undoButton").hide();
        $("#sendButton").hide();
    });
    $("#sendButton").click(function (){
        if(enableSend == true) {
            var objtext = rcmail.http_get('plugin.sendMail');
        }
        enableSend = false;
        window.history.back();
        console.log(obj);
    });
});
