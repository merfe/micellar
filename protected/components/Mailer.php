<?php

class Mailer extends CComponent {

    public function init()
    {
    }

    public function sendTemplate($subject, $template, $params = array())
    {
        $body = Yii::app()->controller->renderPartial($template, $params, true);

        return $this->sendMail($subject, $body);
    }

    function sendMail($subject, $body, $attachments = array())
    {
        $from = Yii::app()->params['email'];
        $to = Yii::app()->params['email_receive'];
        require_once($_SERVER['DOCUMENT_ROOT'] . '/../vendor/swift/swift_required.php');

        $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, 'text/html');

        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername('merfe.handh@gmail.com')
            ->setPassword('handhmerfe');

        $mailer = Swift_Mailer::newInstance($transport);
        return $mailer->send($message);
    }




}
