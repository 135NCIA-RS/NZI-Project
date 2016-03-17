<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<?php Pjax::begin(); ?>    <?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'options' => ['style' => 'max-height:30px;',  //that line make responsive form
		              'max-width:10px;', // and that
		],
		'columns' => [
			['class' => 'yii\grid\SerialColumn'

			],

			'id',
			'title',
			'content:ntext',
			'date',
			'photo',

			['class' => 'yii\grid\ActionColumn',
			 'controller'=>null,
			 'header'=>'Action',
			 'headerOptions' => ['width' => '70'],
			],
		],


	]); ?>
	<?php Pjax::end(); ?></div>
