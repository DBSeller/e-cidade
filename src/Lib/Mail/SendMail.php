<?php

namespace ECidade\Lib\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Exception;

class SendMail extends PHPMailer
{
    /**.
     * @throws Exception
     */
    public function __construct($exceptions = null)
    {

        //Create an instance; passing `true` enables exceptions
        parent::__construct($exceptions);

        if (!file_exists('libs/config.mail.php')) {
            throw new Exception("Arquivo de configuração de e-mail não encontrado!");
        }

        include(modification('libs/config.mail.php'));

        if (empty($sHost)) {
            throw new Exception("Host servidor de e-mail não informado! \nVerifique arquivo de configuração.");
        }

        $this->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        //$this->SMTPDebug = SMTP::DEBUG_SERVER;                  //Enable verbose debug output
        $this->isSMTP();                                          //Send using SMTP
        $this->Host = $sHost;                                     //Set the SMTP server to send through
        $from = empty($sFrom) ? $sUser : $sFrom;
        if ($bAuth) {
            $this->SMTPAuth = $bAuth;                             //Enable SMTP authentication
            $this->Username = $sUser;                             //SMTP username
            $this->Password = $sPass;                             //SMTP password
            if ($sSslt != '') {
                $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      //Enable implicit TLS encryption
            } else {
                $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
        }
        $this->Port = $sPort;
        $this->setFrom($from, $sUser);
    }
}
