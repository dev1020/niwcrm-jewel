<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;

use johnitvn\ajaxcrud\CrudAsset; 


/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
zcxczxc
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
