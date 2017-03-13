<?php

use yii\helpers\Html;
use dosamigos\editable\Editable;
use kartik\widgets\Select2;
use yii\grid\GridView;
use common\models\TmpFigureResult;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpFigureResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Результаты фигур, требующие одобрения';
?>

<div class="tmp-participant-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'figureId',
				'format'    => 'raw',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'figureId',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\Figure::find()->orderBy(['title' => SORT_DESC])->all(), 'id', 'title'),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Выберите фигуру...',
					]
				]),
				'value'     => function (TmpFigureResult $figureResult) {
					return $figureResult->figure->title;
				}
			],
			[
				'attribute' => 'dateForHuman',
				'format'    => 'raw',
				'value'     => function (TmpFigureResult $figureResult) {
					return $figureResult->dateForHuman;
				}
			],
			[
				'label'  => 'Данные о спортсмене',
				'format' => 'raw',
				'value'  => function (TmpFigureResult $figureResult) {
					return $figureResult->athlete->getFullName() . '<br>' . $figureResult->motorcycle->getFullTitle();
				}
			],
			[
				'attribute' => 'timeForHuman',
				'format'    => 'raw',
				'value'     => function (TmpFigureResult $figureResult) {
					return Editable::widget([
						'name'          => 'timeForHuman',
						'value'         => $figureResult->timeForHuman,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $figureResult->id,
							'value'     => $figureResult->timeForHuman,
							'placement' => 'right',
						]
					]);
				}
			],
			[
				'attribute' => 'fine',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (TmpFigureResult $figureResult) {
					return Editable::widget([
						'name'          => 'timeForHuman',
						'value'         => $figureResult->fine,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $figureResult->id,
							'value'     => $figureResult->fine,
							'placement' => 'right',
						]
					]);
				}
			],
			[
				'attribute' => 'videoLink',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (TmpFigureResult $figureResult) {
					return Html::a($figureResult->videoLink, $figureResult->videoLink, ['target' => '_blank']);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (TmpFigureResult $figureResult) {
					$html = '<div class = "pb-10">' . Html::a('<span class="fa fa-check"></span>',
							['/competitions/tmp-figures/approve', 'id' => $figureResult->id],
							['class'       => 'btn btn-success getRequestWithConfirm',
							 'data-id'     => $figureResult->id,
							 'data-action' => '/competitions/tmp-figures/approve',
							 'data-text'   => 'Уверены, что хотите добавить этот результат на сайт?'
							]) . '</div>';
					
					return $html;
				}
			],
			[
				'format' => 'raw',
				'value'  => function (TmpFigureResult $figureResult) {
					$html = '<a href="#" class="btn btn-danger cancelFigureResult"
data-id = ' . $figureResult->id . '
>
<span class="fa fa-remove"></span>
</a>';
					
					return $html;
				}
			]
		],
	]); ?>
</div>

<div class="modal fade" id="cancelFigureResult" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<?= Html::beginForm('', 'post', [
				'id' => 'cancelFigureResultForm',
			]) ?>
            <div class="modal-body">
                <h3>Укажите причину отказа</h3>
				<?= Html::hiddenInput('id', '', ['id' => 'id']) ?>
				<?= Html::textarea('reason', '', ['rows' => 3, 'class' => 'form-control', 'id' => 'smallText']) ?>
				<?php $length = 255; ?>
                <div class="text-right color-green" id="length">осталось символов: <?= $length ?></div>
            </div>
            <div class="alert alert-danger" style="display: none"></div>
            <div class="alert alert-success" style="display: none"></div>
            <div class="modal-footer">
                <div class="form-text"></div>
                <div class="button">
					<?= Html::submitButton('Сохранить', ['class' => 'btn btn-lg btn-block btn-primary']) ?>
                </div>
            </div>
			<?= Html::endForm() ?>
        </div>
    </div>
</div>