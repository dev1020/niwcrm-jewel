<?php
namespace common\components\Ordersmailandpdfwidgets;
 
use Yii;
use yii\base\Component;
use backend\models\Orders;
use yii\helpers\BaseFileHelper;

class Ordersmailandpdf extends Component {

    public function sentMailAndPdf($orderid){
		$order = Orders::findOne($orderid);
        $contentpdf = Yii::$app->controller->renderPartial('@common/mailandpdf/_orderPdf',['order'=>$order]);
                    
		
		$pdfSavePath = Yii::getAlias('@frontend').'/web/invoices/';
		BaseFileHelper::createDirectory($pdfSavePath,0777,false);
		$mpdf=new \Mpdf\Mpdf(['c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0]);
		$mpdf->SetDisplayMode('fullpage'); 
		$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
		$mpdf->showWatermarkImage = true;
		$mpdf->watermarkAngle = 33;
		$mpdf->watermarkImgBehind = true;
		$mpdf->SetWatermarkImage('/images/logo.png','0.08',[150,50],'F');
		$mpdf->WriteHTML($contentpdf);
		$mpdf->Output($pdfSavePath.str_pad($order->id, 10, '0', STR_PAD_LEFT).'.pdf', 'F');
		
		$fileinvoice = $pdfSavePath.str_pad($order->id, 10, '0', STR_PAD_LEFT).'.pdf';
		
		
		//actuall mail config
		/*Yii::$app
		->mailer
		->compose(
			['html' => 'orderStall-html'],
			['stallSingle'=>$stallSingle,'total'=>$stallTotal,'stall'=>$stall,'user'=>Yii::$app->user->identity,'order'=>$stallOrder]
		)
		->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.'|Order Details'])
		->setTo($stall->stall_email)
		->setSubject('Stall Order Details | ' . Yii::$app->name)
		//->setTextBody($this->body)
		->attach($fileinvoice)
		->send();
		*/
    }

    
}
