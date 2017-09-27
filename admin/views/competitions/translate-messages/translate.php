<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\TranslateMessageSource;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TranslateMessageSourceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $letter string */

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

<h4>Всего непереведенных фраз: <?= TranslateMessageSource::find()->count() - \common\models\TranslateMessage::find()->count() ?></h4>

<div class="translate">
    <div class="row table">
        <div class="col-md-12">
			<?= $listView->renderSummary() ?>
        </div>
    </div>
    <div>
		<?= \yii\helpers\Html::beginForm(['/competitions/translate-messages/translate'], 'get') ?>
		<?= \yii\helpers\Html::dropDownList('letter', $letter, [
			'"' => '"', '*' => '*',
			'{' => '{', 'А' => 'А', 'Б' => 'Б', 'В' => 'В', 'Г' => 'Г', 'Д' => 'Д', 'Е' => 'Е', 'Ё' => 'Ё', 'Ж' => 'Ж',
			'З' => 'З', 'И' => 'И', 'К' => 'К', 'Л' => 'Л', 'М' => 'М', 'Н' => 'Н', 'О' => 'О', 'П' => 'П', 'Р' => 'Р', 'С' => 'С',
			'Т' => 'Т', 'У' => 'У', 'Ф' => 'Ф', 'Х' => 'Х', 'Ц' => 'Ц', 'Ч' => 'Ч', 'Ш' => 'Ш', 'Щ' => 'Щ', 'Э' => 'Э', 'Ю' => 'Ю', 'Я' => 'Я'
		],
			['prompt' => 'Выберите начальный символ', 'onchange' => 'this.form.submit()', 'class' => 'form-control']) ?>
        <button type="submit" style="visibility: hidden;" title="Сохранить"></button>
		<?= \yii\helpers\Html::endForm() ?>
    </div>
    <table class="table table-striped table-bordered">
		<?php $form = \yii\bootstrap\ActiveForm::begin([
			'id'                     => 'search',
			'method'                 => 'get',
			'enableClientValidation' => false
		]); ?>
        <thead>
        <tr>
            <th style="width: 30%">
				<?= $dataProvider->sort->link('message') ?>
				<?= $form->field($searchModel, 'message')->textInput()->label(false) ?>
            </th>
            <th style="width: 20%">Комментарий для переводчика</th>
            <!--<th>
				Язык
			</th>-->
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
