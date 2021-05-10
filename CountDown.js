<script type="text/javascript">
    var millis = <?php echo $dbSessionDurationTime; ?>

    function displaytimer(){
        var hours = Math.floor(millis / 36e5),
            mins = Math.floor((millis % 36e5) / 6e4),
            secs = Math.floor((millis % 6e4) / 1000);

            $('.count').html(hours+':'+mins+':'+secs);  
    }

    setInterval(function(){
        millis -= 1000;
        displaytimer();
    }, 1000);

</script>
