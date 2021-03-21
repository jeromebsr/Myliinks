<?php

namespace App;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer extends PHPMailer
{
	/**
	 * @throws Exception
	 * Envoie un mail
	 */
	public function sendMail($from, $to, $subject, $template, $body)
	{
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->confServer();

			//Recipients
			$mail->setFrom($from, 'Myliinks');
			$mail->addAddress($to);     // Add a recipient

			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $body;
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			// Send
			$mail->send();
		} catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: {$mail->ErrorInfo}';
		}
	}
}