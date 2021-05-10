<?php

list($hour,$min,$sec) = explode(':', $dbSessionDuration);
$dbSessionDurationTime = mktime(0,0,0,$hour,$min,$sec);

?>