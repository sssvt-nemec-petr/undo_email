<?php

class undoEmail extends rcube_plugin
{
    public $task = 'mail';
    private $map;

    function init()
    {
        $this->add_hook('message_before_send', [$this, 'mbs']);
        $this->include_script('Demo.js');
        $this->register_action('plugin.cancelMail', [$this, 'test']);
    }

    function test(){
        try {
            $conn = new mysqli("localhost","root", null, "email");
            $stmt = $conn->prepare("insert into unsentemails (receiverMail,senderMail,emailContents) values (?, ?, ?)");
            $a = "123456789Ayoo"; $b = $a; $c = $a;

            $stmt->bind_param('sss',$a,$b,$c);

            $stmt->execute();
            $stmt->close();

            $conn->close();
        }
        catch(Exception $e){
        }
    }

    function request_handler($args){
        try {
            $conn = new mysqli("localhost","root", null, "email");
            $stmt = $conn->prepare("insert into unsentemails (receiverMail,senderMail,emailContents) values (?, ?, ?)");
            $a = "Swag"; $b = $a; $c = $a;

            $stmt->bind_param('sss',$a,$b,$c);

            $stmt->execute();
            $stmt->close();

            $conn->close();
        }
        catch(Exception $e){
        }

        $rcmail = rcmail::get_instance();
        $rcmail->output->command('plugin.callback',['message'=>'swaggin']);
        $rcmail->output->send();
    }

    function mbs($args)
    {

        $regex = "/'txtbody' => '(.+)',\n.*'htmlbody' => (.*)'/";
        preg_match($regex,var_export($args['message'], true), $re);

        foreach (strtolower($re) as $entry)
        {
         if($entry = 'null')
         {
             $entry = null;
         }
        }

        $mailBody = $re[1];
        $htmlBody = $re[2];



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
            //$d = var_export($args['message'], true);


            $stmt->execute();
            $stmt->close();

            $conn->close();
            /*


               $this->rcmail->deliver_message($mailBody, mailFrom, mailTo,
                null, null, null, null);

                Tohle posílá maily, až bude regex, bude to fungovat.
            */
        }
        catch(Exception $e){
        }
        return $args;
    }
   /* function sendMail($args){

    }*/
}






?>

