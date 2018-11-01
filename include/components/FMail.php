<?php
/**
 *
 * 邮箱发送类
 */
class FMail
{
    const MAIL_FROM = 'admin@aladdin-holdings.com';	//发件人

    /**
     *
     * 邮件发送方法
     * @param string $email 邮件发送地址
     * @param string $subject 邮件发送标题
     * @param string $body 邮件发送内容
     */
    public static function send($email, $subject, $body)
    {
        $message = new YiiMailMessage;
        $message->setBody($body, 'text/html');
        $message->subject = $subject;
        $message->addTo($email);
        $message->from = self::MAIL_FROM;
        return  Yii::app()->mail->send($message);
    }
}