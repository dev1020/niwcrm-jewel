<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\web\UploadedFile;
/**
 * Signup form
 */
class OpenSaleForm extends Model
{
    
    
   // public $status;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_type'], 'required'],
            [['other'], 'integer'],
            [['customer_name'], 'string'],
			[['table_id'], 'required', 'when' => function ($model){
					return $model->type == 'table';
				}, 'whenClient' => "function (attribute, value){
					return $('#ordertype').val() == 'table';
			}",'message' => 'Table Number cannot be blank'],
        ];
    }
	
	
}
