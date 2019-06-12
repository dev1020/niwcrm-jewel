<?php

namespace backend\controllers;

use Yii;
use backend\models\Customers;
use backend\models\Companies;
use backend\models\CompanyCustomers;
use backend\models\PromosmsTemplates;
use backend\models\CompanyCustomersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

use common\components\Sms;
use common\components\CountSms;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class PromotionalController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {    
		$request = Yii::$app->request;
		$session = yii::$app->session;
		$company = $session['company.company_id'];
		$company_sms = Companies::findOne($company)->sms_quota;
        $searchModel = new CompanyCustomersSearch();
		$searchModel->company_id = $company;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$promosmsTemplates = PromosmsTemplates::find()->where(['company_id'=>$company])->all();
		if($request->isAjax){
			Yii::$app->response->format = Response::FORMAT_JSON;
			return $this->renderAjax('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'company_sms' => $company_sms,
				'promosmsTemplates' => $promosmsTemplates,	
				'company' => $company,
			]);
		}else{
			//print_r(Yii::$app->request->queryParams);exit;
			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'company_sms' => $company_sms,
				'promosmsTemplates' => $promosmsTemplates,
				'company' => $company,
			]);
		}
    }
	
	public function actionFilterCustomer()
    {    
		$request = Yii::$app->request;
		$session = yii::$app->session;
		$company = $session['company.company_id'];
		$company_sms = Companies::findOne($company)->sms_quota;
        $searchModel = new CompanyCustomersSearch();
		$searchModel->company_id = $company;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$promosmsTemplates = PromosmsTemplates::find()->where(['company_id'=>$company])->all();
		if($request->isAjax){
			Yii::$app->response->format = Response::FORMAT_JSON;
			return $this->renderAjax('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'company_sms' => $company_sms,
				'promosmsTemplates' => $promosmsTemplates,	
				'company' => $company,
			]);
		}else{
			$query = CompanyCustomers::find();
			$query->innerJoin('bussiness','bussiness.bposts_bpost_id = bposts.bpost_id');
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
			]);

			
			
			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'company_sms' => $company_sms,
				'promosmsTemplates' => $promosmsTemplates,
				'company' => $company,
			]);
		}
    }

    
	public function actionMyMail()
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Order #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
			$imapConnection = new \unyii2\imap\ImapConnection() ; 

			$imapConnection->imapPath = '{saltlake.in:993/imap/ssl}INBOX';
			$imapConnection->imapLogin = 'hr@saltlake.in';
			$imapConnection->imapPassword = 'saltlaKe255';
			$imapConnection->serverEncoding = 'encoding'; // utf-8 default.
			$imapConnection->attachmentsDir = '/';


			//4th Param _DIR_ is the location to save attached files 
			//Eg: /path/to/application/mail/uploads.
			$mailbox = new \unyii2\imap\Mailbox($imapConnection);
			$box = $mailbox->getListingFolders();
			
			//print_r($box);
			//exit;
			$mailIds = $mailbox->searchMailBox('ALL');// Prints all Mail ids.
			
			$mails = $mailbox->getMailsInfo($mailIds);
            return $this->render('mail', [
				'mails'=>$mails,
            ]);
        }
    }
	
	

    
     /**
     * Delete multiple existing Order model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionBulkSms()
    {   
		$request = Yii::$app->request;
		$session = yii::$app->session;
		$company = $session['company.company_id'];
		$companymodel = Companies::findOne($company);
		$smstext = rawurlencode($request->post( 'smstext' ));
		$sms = new Sms();
		$countSms = new CountSms();
        $ids = explode(',',$request->post('ids'));

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
			
			foreach ( $ids as $id ){
				if($countSms->countquota($company)){
					if(CompanyCustomers::find()->where(['id'=>$id])->exists()){
						
						$contact = CompanyCustomers::findOne($id)->cust->contact;
						//$sms->sendSms($contact,$smstext);
						// reduce sms from quota
						
						$companymodel->sms_quota = $companymodel->sms_quota - 1;
						$companymodel->save();
					}else{
						continue;
					}
				}else{
					return ['status'=>false,'msg'=>'Recharge Your SMS Pack'];
				}
			}
            return ['status'=>true,'msg'=>'Send Successfully'];
        }       
    }
	public function actionGettemplates($id)
    {        
        $request = Yii::$app->request;
		$model = PromosmsTemplates::findOne($id);
		

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['content'=>$model->sms_body];
        }
       
    }

   
}
