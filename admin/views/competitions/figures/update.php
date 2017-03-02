<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Figure */
/* @var $success integer */

$this->title = 'Редактирование фигуры: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Фигуры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="figure-update">
	
	<?php if ($success) { ?>
        <div class="alert alert-success">
            Изменения успешно сохранены
        </div>
	<?php } ?>
	
	<?= Collapse::widget([
		'items' => [
			[
				'label'   => 'Описание фигуры',
				'content' => $this->render('_form', ['model' => $model])
			],
		]
	]);
	?>

    <h3>Результаты</h3>
	<?php Modal::begin([
		'header'       => '<h2>Выберите дату заездов</h2>',
		'toggleButton' => [
			'label' => 'Добавить результаты',
            'class' => 'btn btn-success'
		]
	]) ?>
	<?= \yii\bootstrap\Html::beginForm(['add-results'], 'get', ['id' => 'xlsForm']) ?>
    <div class="row">
        <div class="col-md-4">
			<?php
			echo DatePicker::widget([
				'type'          => DatePicker::TYPE_INPUT,
				'name'          => 'dateStart',
				'name2'         => 'dateEnd',
				'value'         => date('d.m.Y', time()),
				'language'      => 'ru',
				'options'       => ['placeholder' => 'Дата'],
				'removeButton'  => false,
				'pluginOptions' => [
					'autoclose'      => true,
					'format'         => 'dd.mm.yyyy',
					'todayHighlight' => true
				]
			]);
			?>
        </div>
        <div class="col-md-2">
			<?= Html::submitButton(\Yii::t('app', 'ОК'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
	<?= Html::endForm() ?>
	<?php Modal::end() ?>
</div>
