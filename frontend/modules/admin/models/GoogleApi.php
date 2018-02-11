<?
namespace app\modules\admin\models;

use Yii;
use yii\helpers\Json;
use app\modules\admin\models\PurchasingOrder;
use frontend\models\MainDevices;
use yii\helpers\FileHelper;
use app\modules\admin\models\Client;
use Google_Service_Calendar_Event;
use Google_Service_Calendar;
use Google_Client;
use Google_Service_Exception;

class GoogleApi
{	
	/**
	* Returns an authorized API client.
	* @return Google_Client the authorized client object
	*/
	public static function getClient() {
		$client = new Google_Client();
		$client->setApplicationName(APPLICATION_NAME);
		$client->setScopes(SCOPES);
		$client->setAuthConfig(CLIENT_SECRET_PATH);
		$client->setAccessType('offline');
		
		// Load previously authorized credentials from a file.
		$credentialsPath = GoogleApi::expandHomeDirectory(CREDENTIALS_PATH);
		if (file_exists($credentialsPath)) {
			$accessToken = json_decode(file_get_contents($credentialsPath), true);
		}

		$client->setAccessToken($accessToken);

		// Refresh the token if it's expired.
		if ($client->isAccessTokenExpired()) {
			$refreshToken = $client->getRefreshToken();
			$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			$accessToken = $client->getAccessToken();
			$accessToken['refresh_token'] = $refreshToken;
			// echo '<pre>';
			// var_dump($accessToken);
			// exit;			
			file_put_contents($credentialsPath, json_encode($accessToken));
		}
		return $client;
	}
	
	/**
	* Expands the home directory alias '~' to the full path.
	* @param string $path the path to expand.
	* @return string the expanded path.
	*/
	public static function expandHomeDirectory($path) {
		$homeDirectory = getenv('HOME');
		if (empty($homeDirectory)) {
			$homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
		}
		return str_replace('~', realpath($homeDirectory), $path);
	}	
}