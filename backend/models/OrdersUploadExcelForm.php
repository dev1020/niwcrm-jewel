<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class OrdersUploadExcelForm extends Model
{
    
    
    public $excel_file;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['excel_file'], 'required'],
            [['excel_file'], 'file','extensions' => 'xls, xlsx', 'mimeTypes' => 'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',],
            
        ];
    }
	
	
}
