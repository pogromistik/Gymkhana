<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\TranslateMessageSource;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TranslateMessageSourceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Слова для перевода';

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
	'itemView'     => 'messagesRow'
]);
?>

<div class="row table">
	<div class="col-md-12">
		<?= $listView->renderSummary() ?>
	</div>
</div>

<div class="pb-30">
	<?= Html::a('Добавить сообщение', ['/competitions/translate-messages/create'], ['class' => 'btn btn-success']) ?>
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
        <th>
			<div class="pb-45">Комментарий для переводчика</div>
        </th>
		<th>
			<?= $dataProvider->sort->link('status') ?>
			<?= $form->field($searchModel,
				'status')->dropDownList(TranslateMessageSource::$statusesTitle,
				['prompt' => 'Статус', 'onchange' => 'this.form.submit()'])->label(false) ?>
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
