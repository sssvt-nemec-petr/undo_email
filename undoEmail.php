<?php
class undoEmail extends rcube_plugin
{
    public $dbHostname;
    public $dbUsername;
    public $dbPassword;
    public $dbDatabase;
    public $sendingMail;
    public $task = 'mail';
    private $map;



    function init()
    {
        $this->add_hook('message_before_send', [$this, 'mbs']);
        $this->include_script('buttons.js');
        $this->register_action('plugin.cancelMail', [$this, 'deleteMail']);
        $this->register_action('plugin.sendMail', [$this, 'sendMail']);

        $config = parse_ini_file('config.ini');

        $this->dbHostname = $config['db_hostname'];
        $this->dbUsername = $config['db_username'];
        $this->dbPassword = $config['db_password'];
        $this->dbDatabase = $config['db_database'];

    }

    function deleteMail()
    {
        try {
            $conn = new mysqli($this->dbHostname, $this->dbUsername, $this->dbPassword, $this->dbDatabase);
            $stmt = ('DELETE FROM unsentemails ORDER BY emailID DESC LIMIT 1');

            $conn->query($stmt);

            $conn->close();
        } catch (Exception $e) {
        }
    }

    function sendMail(){
        $conn = new mysqli($this->dbHostname, $this->dbUsername, $this->dbPassword, $this->dbDatabase);
        $query = 'select * from unsentemails order by emailId desc limit 1';

        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $from = $row["senderMail"];
            $to = $row["receiverMail"];
            $mailBody = $row["mailBody"];
            $htmlBody = $row["htmlBody"];
            $subject = $row["subject"];
        }
        $stmt = ('DELETE FROM unsentemails ORDER BY emailID DESC LIMIT 1');

        $conn->query($stmt);

        $mime = new Mail_mime([]);

        $objDateTime = new DateTime('NOW');
        $dateTimeNow = $objDateTime->format(DateTime::ISO8601);

        $mime->setTXTBody($mailBody);
        $mime->setHTMLBody($htmlBody);
        $mime->get(array('head_charset' => 'utf-8'));
        $mime->get(array('text_charset' => 'utf-8'));
        $mime->headers(['BeforeSend' => 'false', 'From' => $from,'Subject' => $subject, 'Date' => $dateTimeNow]);


        $rcmail = rcmail::get_instance();

        $smpt_opts = ['dsn' => 'false'];
        $smtp_error = null;
        $mailbody_file = null;

        $rcmail->deliver_message($mime,$from,$to,$smtp_error,$mailbody_file,$smpt_opts,true);
        $conn->close();
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
            $conn = new mysqli($this->dbHostname, $this->dbUsername, $this->dbPassword, $this->dbDatabase);
            $stmt = $conn->prepare("insert into unsentemails (receiverMail,senderMail,mailBody, htmlBody,subject) values (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssss', $to, $from, $mailBody, $htmlBody, $mailSubject);

            $stmt->execute();
            $stmt->close();

            $conn->close();
        }
        catch (Exception $e) {
        }
        return $args;
    }
}

}
?>

