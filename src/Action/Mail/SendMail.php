<?php

namespace App\Action\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

use App\Repository\QueryFactory;

require __DIR__.'./../../../vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__.'./../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__.'./../../../vendor/phpmailer/phpmailer/src/SMTP.php';

    final class SendMail
{

    /**
     * @var QueryFactory
     */
    private $queryFactory;

    public $topic = "Empty subject";
    public $content = "Empty body";
    public $id_consultation;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    function getMailAdress( $id ) {
        $query = $this->queryFactory->newSelect('consultation')->select([
            'email',
        ])->andWhere(['id_consultation' => $id]);

        $result = $query->execute();

        return $result;
    }

    function send() {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.mailtrap.io';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = '63bd291a50651f';                     // SMTP username
            $mail->Password   = 'aea8eff261d903';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        
            //Recipients
            $mail->setFrom('poczta@mailtrap.io', 'Administrator');
            $mail->addAddress($this->getMailAdress($this->id_consultation), '');     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo('administracjaserwisu@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');
        
            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $this->topic;
            $mail->Body    = $this->content;
        
            $html = new \Html2Text\Html2Text($mail->Body);
            $mail->AltBody = $html->getText();
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo} ";
        }
    }
}


?>