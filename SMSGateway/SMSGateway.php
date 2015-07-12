<?php
/**
 * Created by PhpStorm.
 * User: charith
 * Date: 7/11/15
 * Time: 8:52 AM
 */

namespace Base\SMSGatewayBundle\SMSGateway;


class SMSGateway {
    protected $mailer;
    protected $server_parameters;
    protected $servername;
    protected $username;
    protected $password;
    protected $db_name;

    public function __construct($mailer,$server_parameters)
    {
        $this->mailer = $mailer;
        $this->server_parameters = $server_parameters;
        $this->setServerInfo();
    }

    private function setServerInfo()
    {
        $this->servername = $this->server_parameters['servername'];
        $this->username = $this->server_parameters['username'];
        $this->password = $this->server_parameters['password'];
        $this->db_name = $this->server_parameters['db_name'];
    }

    public function sendSMS($number,$message)
    {
        $op_code = substr($number,0,3);
        $sender_ID = null;

        switch ($op_code){
            case '071':
                $sender_ID = 'mobitel';
                break;
            case '077':
            case '076':
                $sender_ID = 'dialog';
                break;
            case '078':
                $sender_ID = 'hutch';
                break;
            case '072':
                $sender_ID = 'etisalat';
                break;
            case '075':
                $sender_ID = 'airtel';
                break;
        }

        // Create connection
        $conn = new \mysqli($this->servername, $this->username, $this->password, $this->db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        else{
            $sql = "INSERT INTO outbox (DestinationNumber, TextDecoded, SenderID, CreatorID, Coding)
                VALUES ('".$number."', '".$message."', '".$sender_ID."', '".$sender_ID."', 'Default_No_Compression')";
            $conn->query($sql);
        }
        $conn->close();
    }

    public function sendEmail($subject,$fromAdd,$toAdd,$msgBody)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromAdd)
            ->setTo($toAdd)
            ->setBody($msgBody)
        ;
        $this->mailer->send($message);
    }
} 