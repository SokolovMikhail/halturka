<?
namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use frontend\models\helpers\StringHelper;


class ImageUploadSingle extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif, bmp'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'imageFile' => 'Прикрепить картинку',

        ];
    }

	
    public function upload()
    {	
        if ($this->validate()) {
			$file = $this->imageFile;
			$fileName = StringHelper::transliterate($file->baseName) . '.' . $file->extension;
            $this->imageFile->saveAs(Yii::getAlias('@app') . '/uploads/brands/' . $fileName);
			
			$result = [
				'path' => Yii::getAlias('@app') . '/uploads/brands/' . $fileName,
				'fileName' => $fileName,
				'type' => 'image'
			];	
				
            return $result;
        } else {

            return false;
        }
    }
}