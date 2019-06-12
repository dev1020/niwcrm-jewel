<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;



/* @var $this yii\web\View */
/* @var $searchModel backend\models\BpostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reports | Filter Listings';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>

<div class="container">
	<div class="">
		<h2>Filtering Listings</h2>
	</div>
    <?php Pjax::begin();?>
    <div>
        <button class="btn btn-primary filter-button" data-filter="all">All</button>
		<?= Html::a('Category Wise', ['filterwise-listing','mode'=>'category'],[ 'class'=>'btn btn-default filter-button', 'title'=>'Category Wise']) ?>
		<?= Html::a('Location Wise', ['filterwise-listing','mode'=>'location'],['data-pjax'=>1, 'class'=>'btn btn-default filter-button', 'title'=>'Location Wise']) ?>
		<?= Html::a('Package Wise', [''],['class'=>'btn btn-default filter-button', 'title'=>'Package Wise']) ?>
    </div>
	
	<div class="bussiness-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/'.$mode.'_columns.php'),
            'toolbar'=> [
                ['content'=>					
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Bussinesses listing',
                'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>false,
            ]
        ])?>
    </div>
	</div>
	
</div>


<?php Pjax::end();?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "size"=>'modal-lg',
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
