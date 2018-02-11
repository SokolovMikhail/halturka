<?
namespace frontend\models\helpers;

use \PHPExcel;
use Yii;

class xlsReport
{	

	/**
     * Объект для работы с .xls
     */
	public $xls;


	/**
     * Имя файла
     */
	public $fileName = 'newjournal';


	/**
     * Стили
     */
	public $styleBold = [
		'font'	=> [
			'bold' => true,
		]
	];
		
	//рамки
	public $styleBorders = [
		'font'=>[
			'name' => 'Arial',
			'size' => 8,
		],
		'borders' => [
			//внешняя рамка
			'outline' => [
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
				'color'	=> [
					'rgb'=>'2e6da4'
				]
			],
			//внутренняя
			'allborders' => [
				'style'	=> \PHPExcel_Style_Border::BORDER_THIN,
				'color'	=> [
					'rgb'=>'2e6da4'
				]
			],
		],
		'alignment' => [
			'horizontal'	=> \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'		=> \PHPExcel_Style_Alignment::VERTICAL_CENTER,
		],
	];

	public $styleFontBig = [
		'font'=>[
			'name' => 'Calibri',
			'size' => 12,
		]
	];
	public $styleFont12 = [
		'font'=>[
			'name' => 'Arial',
			'size' => 12,
		],
		'borders' => [
			//внешняя рамка
			'outline' => [
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
				'color'	=> [
					'rgb'=>'2e6da4'
				]
			],
			//внутренняя
			'allborders' => [
				'style'	=> \PHPExcel_Style_Border::BORDER_THIN,
				'color'	=> [
					'rgb'=>'2e6da4'
				]
			],
		],
		'alignment' => [
			'horizontal'	=> \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'		=> \PHPExcel_Style_Alignment::VERTICAL_CENTER,
		],
	];
	
	public $styleHeader = [
		'font' => [
			'bold' => true,
			'color'	=> [
				'rgb' => 'ffffff'
			]
		],
		'fill' => [
			'type'	=> \PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> [
				// 'rgb' => '286090'
				'rgb' => '3183a3'
			]
		]
	];
	
	public $styleSubHeader = [
		'fill'	=> [
			'type'	=> \PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> [
				'rgb'	=> 'EEEEEE'
			]
		],
		'font' => [
			'bold' => true,
		],
	];
	
	public $yelowBg = [
		'fill'	=> [
			'type'	=> \PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> [
				'rgb'	=> 'ffeb9c'
			]
		]
	];
	public $redBg = [
		'fill'	=> [
			'type'	=> \PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> [
				'rgb'	=> 'ffc7ce'
			]
		]
	];
	public $greenBg = [
		'fill'	=> [
			'type'	=> \PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> [
				'rgb'	=> 'b9ce37'
			]
		]
	];	
	public $styleTitle = [
		'font' => [
			'bold' => true,
			'size' => 20,
		],
	];
	
	public $styleMegaTitle = [
		'font' => [
			'bold' => true,
			'size' => 40,
		],
		'alignment' => [
			'rotation'   => 0,
			'wrap'       => true,
			'horizontal'	=> \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'		=> \PHPExcel_Style_Alignment::VERTICAL_CENTER,	
		]
	];


	/**
	 * Конструктор
	 */
	public function __construct()
    {
    	$this->xls = new PHPExcel();
    	$this->xls->setActiveSheetIndex(0);
    }


	/**
	 * Динамический вызов методов
	 */
	public function __call($methodName, $parameters) {
		if(method_exists ($this->xls , $methodName)){
			return call_user_func_array([$this->xls, $methodName], $parameters);
		}
		else{
			return call_user_func_array([$this, $name], $parameters);
		}
	}
	

	/**
     * Возвращает текущий лист
     * TO_DO: заменени на динамический вызов методов $this->xls
     */
	public function getSheet(){
		return $this->xls->getActiveSheet();
	}
	

	/**
     * Сохранение файла на сервере
     * TO_DO: Устаревший, заменен на save()
     */
	public function saveXlsReport(){				
		$objWriter = \PHPExcel_IOFactory::createWriter($this->xls, 'Excel2007');
		$name = '';
		if(!$this->fileName){
			$name = 'newjournal.xlsx';
		}else{
			$name = $this->fileName . ".xlsx";
		}
		$filePath =	Yii::getAlias('@frontend') . '/web/assets/' . $name;	
		$objWriter->save($filePath);
		return $filePath;
	}


	/**
     * Сохранение файла на диск
     */
	public function save(){				
		$objWriter = \PHPExcel_IOFactory::createWriter($this->xls, 'Excel2007');
		$filePath =	Yii::getAlias('@frontend') . '/web/assets/' . $this->fileName . '.xlsx';	
		$objWriter->save($filePath);
		return $filePath;
	}
	

	/**
     * Отправка файла в ответе
     * TO_DO: Устаревший, заменен на send()
     */
	public function sendXlsReport(){
		header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		header ( "Cache-Control: no-cache, must-revalidate" );
		header ( "Pragma: no-cache" );
		header ( "Content-type: application/vnd.ms-excel" );
		if(!$this->fileName){
			header ( "Content-Disposition: attachment; filename=newjournal.xlsx" );
		}else{
			header ( "Content-Disposition: attachment; filename=".str_replace(',', ' ', $this->fileName).".xlsx" );
		}			
		$objWriter = \PHPExcel_IOFactory::createWriter($this->xls, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}


	public function send(){
		header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
		header ( "Cache-Control: no-cache, must-revalidate" );
		header ( "Pragma: no-cache" );
		header ( "Content-type: application/vnd.ms-excel" );
		header ( "Content-Disposition: attachment; filename=".$this->fileName.".xlsx" );	
		$objWriter = \PHPExcel_IOFactory::createWriter($this->xls, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}



}