<?php

namespace App\Services;

use \App\Config;
use \App\Services\EntityService as Entities;
use \App\Services\PropertyService as Properties;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use \App\Services\ToastService as Toast;

class EmailService
{

	
	public static function send($to, $subject, $sbody){
		
		$mail=new PHPMailer();
		$mail->IsSMTP();    
		$mail->Port = Properties::getProperty("SMTP_PORT")->getValue();
		$mail->SMTPAuth = true;               
		$mail->Username= Properties::getProperty("SMTP_USERNAME")->getValue();
		$mail->Password = Properties::getProperty("SMTP_PASSWORD")->getValue();
		$mail->Host= Properties::getProperty("SMTP_HOST")->getValue();
		$mail->SMTPSecure = 'tls';   
		$mail->From = Properties::getProperty("SMTP_FROM")->getValue();
		$mail->FromName = Properties::getProperty("SMTP_FROM_NAME")->getValue();
		$mail->AddAddress($to); 
		$mail->MsgHTML($sbody);
		$mail->isHTML(true);
		$mail->Body    = $sbody;
		$mail->Subject = $subject;
		
		if(!$mail->Send()) {
			Toast::throwError("Email not sent", $mail->ErrorInfo);			
		}
	}
	
	
	public static function sendTemplate($template, $to, $subject, $arguments){
		
		$template = DIR_VIEWS . '/Email/' . $template . ".html";
		if(file_exists($template)){
			
			$content = file_get_contents($template);
			
			foreach($arguments as $key => $value){
				$content = str_replace("{{" . $key . "}}", $value, $content);
			}
			
			self::send($to, $subject, $content);
		
		
			
		}else{
			
			Toast::throwError("Email template not found", "No template was found for $template");
		}
		
	
		
	}
	
	
	/*
	
	Emails which can be sent
	
	*/
	
	
	/* Sends an email to the user to reset password as a new user*/
	public static function newUserEmail($userId, $token){
		
		$user = Entities::findEntity("user", $userId);	
		self::sendTemplate('new_user',$user->getEmail(),'You\'ve been invited to join PRATS', array('name' => $user->getFirstName(), 'link' => _URL_ROOT . '/reset-password?token=' . $token));
		
	}	
	
	/* Sends an email to the user with a link to reset their password*/
	public static function resetPasswordEmail($userId, $token){
		
		$user = Entities::findEntity("user", $userId);	
		self::sendTemplate('reset_password',$user->getEmail(),'Password Reset', array('name' => $user->getFirstName(), 'link' => _URL_ROOT . '/reset-password?token=' . $token));
		
	}		
	
	
	
	
}

