<?php
class undo_email extends rcube_plugin
{
    public $task = 'mail';
    private $map;



    function init()
    {
        $this->add_hook('message_before_send', [$this, 'mbs']);
        $this->include_script('buttons.js');
        $this->register_action('plugin.cancelMail', [$this, 'deleteMail']);
        $this->register_action('plugin.sendMail', [$this, 'sendMail']);

        $config = parse_ini_file('config.ini');
    }

    function deleteMail()
    {
        $sql= ("DELETE FROM unsent_emails ORDER BY email_id DESC LIMIT 1");
        $this->rc->db->query($sql);
    }

    function sendMail(){
        $query = "select * from unsent_emails order by email_id desc limit 1";

        foreach ($this->rc->db->query($query) as $row){
            $from = $row["sender_mail"];
            $to = $row["receiver_mail"];
            $mailBody = $row["mail_mail"];
            $htmlBody = $row["html_body"];
            $subject = $row["subject"];
        }

        $this->deleteMail();

        $mime = new Mail_mime([]);

        $objDateTime = new DateTime('NOW');
        $dateTimeNow = $objDateTime->format(DateTime::ISO8601);

        $mime->setTXTBody($mailBody);
        $mime->setHTMLBody($htmlBody);
        $mime->get(['head_charset' => 'utf-8']);
        $mime->get(['text_charset' => 'utf-8']);
        $mime->headers(['BeforeSend' => 'false', 'From' => $from,'Subject' => $subject, 'Date' => $dateTimeNow]);


        $rcmail = rcmail::get_instance();

        $smpt_opts = ['dsn' => 'false'];
        $smtp_error = null;
        $mailbody_file = null;

        $rcmail->deliver_message($mime,$from,$to,$smtp_error,$mailbody_file,$smpt_opts,true);
    }

    function mbs($args)
    {
        if($args['message']->headers()['BeforeSend'] != 'false') {
        $txt = var_export($args['message'], true);

        $regex = "/'txtbody' => (.+),.*\n.*'htmlbody'.*=> (.*),[\S\s]*'Subject' => '(.*)'/m";
        preg_match($regex, $txt, $re);
        for ($i = 0; $i < count($re); $i++) {
            $entry = $re[$i];
            if (strtolower($entry) == 'null') {
                $re[$i] = '';
            } else if (strlen($entry) > 1 && $entry[0] == "'" && $entry[strlen($entry) - 1] == "'") {
                $re[$i] = substr($entry, 1, strlen($entry) - 2);
            }
        }

        $mailBody = $re[1];
        $htmlBody = $re[2];
        $mailSubject = $re[3];
        $to = $args['mailto'];
        $from = $args['from'];


        $args['abort'] = true;
        $args['error'] = null;
        $args['result'] = false;

        try {
            $sqlquery = "insert into unsent_emails (receiver_mail,sender_mail,mail_body,html_body,subject) values ('$to','$from','$mailBody','$htmlBody','$mailSubject')";
            $this->rc->db->query($sqlquery);
        }
        catch (Exception $e) {
        }
        return $args;
    }
}

}
?>

