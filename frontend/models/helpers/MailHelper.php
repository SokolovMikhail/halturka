<?
namespace frontend\models\helpers;

use Yii;


class MailHelper
{
	public static function SendToUser($message, $addressTo, $attachements = [], $topic = false)
	{
		$bodyHTML = $message;
		$mail = Yii::$app->mailer->compose()
		->setFrom(Yii::$app->mailer->getTransport()->getUsername())
		->setTo($addressTo)
		->setSubject($topic ? $topic : 'Tehnovizor inform')
		->setHtmlBody($bodyHTML);
		
		if($attachements){
			foreach($attachements as $item){
				$mail->attach($item);
			}
		}
		$mail->send();
	}
}