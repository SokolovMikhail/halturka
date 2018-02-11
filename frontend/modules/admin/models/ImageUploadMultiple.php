<?
namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use frontend\models\helpers\StringHelper;

class ImageUploadMultiple extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [
				['imageFile'],
				'file',
				'skipOnEmpty' => true,
				// 'extensions' => 'png, jpg, jpeg, gif, bmp',
				'maxFiles' => 10,
				'maxSize' => 50000 * 1024,
			],
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
		$result = [];
        if ($this->validate()) {
			$result = [];
			foreach ($this->imageFile as $file) {
				$fileName = StringHelper::transliterate($file->baseName) . '.' . $file->extension;
				
				if(file_exists(Yii::getAlias('@app') . '/uploads/' . $fileName)){//Если файл уже существует
					$i=0;
					do {
						$fileName = StringHelper::transliterate($file->baseName) .'-'.++$i. '.' . $file->extension;
					} while (file_exists(Yii::getAlias('@app') . '/uploads/' . $fileName));
				}	
				
				$file->saveAs(Yii::getAlias('@app') . '/uploads/' . $fileName);
				$result[] = [
					'path' => Yii::getAlias('@app') . '/uploads/' . $fileName,
					'fileName' => $fileName,
					'type' => 'image'
				];	
			}	
            return $result;
        } else {
            return $result;
        }
    }
}