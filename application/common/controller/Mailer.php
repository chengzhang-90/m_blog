<?php
namespace app\common\controller;
use PHPMailer\PHPMailer\PHPMailer;
class Mailer
{
	/**
	 * 系统邮件发送函数
	 * @param string $tomail 接收邮件者邮箱
	 * @param string $name 接收邮件者名称
	 * @param string $subject 邮件主题
	 * @param string $body 邮件内容
	 * @param string $attachment 附件列表
	 * @return boolean
	 * @author static7 <static7@qq.com>
	 */
	function send_mail($tomail, $name, $subject = '', $body = '', $attachment = null) {
	    $mail=new PHPMailer();
            //邮件调试模式
            //$mail->SMTPDebug =2;  
            //设置邮件使用SMTP
            $mail->isSMTP();
            // 设置邮件程序以使用SMTP
            $mail->Host = 'smtp.163.com';
            // 设置邮件内容的编码
            $mail->CharSet='UTF-8';
            // 启用SMTP验证
            $mail->SMTPAuth = true;
            // SMTP username
            $mail->Username = 'Frank_hhm@163.com';
            // SMTP password
            $mail->Password = 'HHM668138';
            // 启用TLS加密，`ssl`也被接受
//            $mail->SMTPSecure = 'tls';
            // 连接的TCP端口
           	$mail->Port = 25;
            //设置发件人
            $mail->setFrom('Frank_hhm@163.com', 'Frank');
            // 添加收件人1
            $mail->addAddress($tomail, $name);     // Add a recipient
//            $mail->addAddress('ellen@example.com');               // Name is optional
//            收件人回复的邮箱
           // $mail->addReplyTo('595700844@qq.com', 'Frank');
//            抄送
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');
            //附件
            // if ($attachment!=null) {
            // 	$mail->addAttachment($attachment);         // Add attachments
            // }
	       
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            //Content
            // 将电子邮件格式设置为HTML
            $mail->isHTML(true);
            $mail->Subject = $subject ;
            $mail->Body    = $body;
	        //$mail->AltBody = '这是非HTML邮件客户端的纯文本';
            $mail->isSMTP();
            if(!$mail->send()){// 发送邮件
	            return $mail->ErrorInfo;
	        // echo "Message could not be sent.";
	        // echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息
	        }else{
	            return 1;
	        }
    }
}