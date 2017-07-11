<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\TranslateMessageSource;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TranslateMessageSourceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Переводы';

$listView = new \yii\widgets\ListView([
	'dataProvider' => $dataProvider,
	'layout'       => "{items}\n{pager}",
	'options'      => [
		'tag' => 'tbody'
	],
	'pager'        => [
		'firstPageLabel' => '<<',
		'lastPageLabel'  => '>>',
		'prevPageLabel'  => '<',
		'nextPageLabel'  => '>',
	],
	'itemView'     => 'translateRow'
]);
?>

<div class="translate">
	<div class="row table">
		<div class="col-md-12">
			<?= $listView->renderSummary() ?>
		</div>
	</div>
	
	<table class="table table-striped table-bordered">
		<?php $form = \yii\bootstrap\ActiveForm::begin([
			'id'                     => 'search',
			'method'                 => 'get',
			'enableClientValidation' => false
		]); ?>
		<thead>
		<tr>
			<th>
				<?= $dataProvider->sort->link('message') ?>
				<?= $form->field($searchModel, 'message')->textInput()->label(false) ?>
			</th>
            <th>Комментарий для переводчика</th>
			<th>
				Язык
			</th>
			<th>
				Перевод
			</th>
			<th>
				<button type="submit" style="visibility: hidden;" title="Сохранить"></button>
			</th>
		</tr>
		</thead>
		
		<?php $form->end() ?>
		<tbody>
		
		
		<?php
		foreach ($dataProvider->models as $index => $model) {
			echo $listView->renderItem($model, $model->id, $index);
		}
		?>
		</tbody>
	</table>
	<?= $listView->renderPager() ?>
</div>
