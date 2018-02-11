<?
namespace app\modules\admin\models;

use Yii;
use yii\helpers\Json;
use CURLFile;

class MainServerInterface
{	
	/**
	 * Отправка картинки на главный сервер
	 */
	public static function sendImages($names, $ownerId, $ownerType)
	{
		$myCurl = curl_init();
		$data = [];//Массив с файлами
		foreach($names as $item){
			$cfile = new CURLFile(Yii::getAlias('@app') . '\uploads\\' . $item ,'image/png', $item);
			$data[$cfile->postname ] = $cfile;
		}

		curl_setopt_array($myCurl, array(
			CURLOPT_URL 			=> 'https://main.tehno-vizor.ru/rest/images-server/download/?access-token=gGYOj5x72eSyDsLlPHyc4-pNknpLS94h&ownerId=' . $ownerId . '&ownerType=' . $ownerType,
			CURLOPT_RETURNTRANSFER	=> true,                                                                             
			CURLOPT_TIMEOUT 		=> 10,
			CURLOPT_POST			=> true,
			CURLOPT_POSTFIELDS		=> $data,
		));	
				
		$response = curl_exec($myCurl);
		foreach($names as $item){
			unlink(Yii::getAlias('@app') . '\uploads\\' . $item);//Удаление временных файлов, которые были отправлены
		}		
	}
	
}