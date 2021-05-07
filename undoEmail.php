<?php

class undoEmail extends rcube_plugin
{
    public $task = 'mail';
    private $map;

    function init()
    {
        $this->add_hook('message_before_send', [$this, 'mbs']);
    }

    function mbs($args)
    {

        $regex = "/'txtbody' => '(.+)'/";
        preg_match($regex,var_export($args['message'], true), $re);
        $mailBody = $re[1];
        //$mime = $args['message']->encode();
        //$body = $mime['body'];
        //$headers = $mime['headers'];
        $args['abort'] = true;
        $args['error'] = 'TestError';
        $args['result'] = false;

        try {
            $conn = new mysqli("localhost","root", null, "email");
            $stmt = $conn->prepare("insert into unsentemails (receiverMail,senderMail,emailContents) values (?, ?, ?)");
            $stmt->bind_param('sss',$a,$b,$c);

            $a = $args['mailto'];
            $b = $args['from'];
            $c = $mailBody;

            $stmt->execute();
            $stmt->close();

            $conn->close();
        }
        catch(Exception $e){
//            $conn = new mysqli("localhost","root", null, "email");
//            $sql = "insert into unsentemails (receiverMail,senderMail,emailContents) values ('err','err','err')";
//            $conn->query($sql);
//            $conn->close();
        }

        return $args;
    }
}






?>

