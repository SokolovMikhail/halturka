<?php
namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use frontend\models\helpers\StringHelper;
use Yii;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'docx'],
        ];
    }
     public function attributeLabels()
    {
        return [
            'imageFile' => 'Загрузить новый шаблон',
        ];
    }   
    public function upload()
    {
        if ($this->validate()) {
			$fileName = StringHelper::transliterate($this->imageFile->baseName) . '.' . $this->imageFile->extension;			
			if(file_exists('uploads/' . $fileName)){//Если файл уже существует
				$i=0;
				do {
					$fileName = StringHelper::transliterate($this->imageFile->baseName) .'-'.++$i. '.' . $this->imageFile->extension;
				} while(file_exists('uploads/' . $fileName));
			}			
            $this->imageFile->saveAs('uploads/' . $fileName);
            return $fileName;
        } else {
            return false;
        }
    }
}
