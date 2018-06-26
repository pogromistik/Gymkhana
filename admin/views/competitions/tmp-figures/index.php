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
<div class="alert required-alert-info">
    Мы принимаем только результаты, для которых есть видео заезда (при этом обязательно должна быть электронная
    телеметрия! результаты, засеченные телефоном не могут считаться официальными и не принимаются на сайт. исключение -
    результат не влияет на класс спортсмена + класс спортсмена ниже D1) или
    этот результат есть в группе <a href="https://vk.com/motogymkhana_ru" target="_blank">Мото Джимхана [Sport]</a>
    (или <a href="https://vk.com/topic-35972290_30425335?offset=0" target="_blank">МотоДжимхана</a> для GP8)
</div>

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
					return Editable::widget([
						'name'          => 'figureId',
						'value'         => $figureResult->figure->title,
						'url'           => 'update',
						'type'          => 'select',
						'mode'          => 'pop',
						'clientOptions' => [
							'pk'        => $figureResult->id,
							'placement' => 'right',
							'select'    => [
								'width' => '124px'
							],
							'source'    => \yii\helpers\ArrayHelper::map(\common\models\Figure::getAll(), 'id', 'title'),
						]
					]);
				}
			],
			[
				'attribute' => 'dateForHuman',
				'format'    => 'raw',
				'value'     => function (TmpFigureResult $figureResult) {
					return Editable::widget([
						'name'          => 'dateForHuman',
						'value'         => $figureResult->dateForHuman,
						'url'           => 'update',
						'type'          => 'text',
						'mode'          => 'inline',
						'clientOptions' => [
							'pk'        => $figureResult->id,
							'value'     => $figureResult->dateForHuman,
							'placement' => 'right',
						]
					]);
				}
			],
			[
				'label'  => 'Данные о спортсмене',
				'format' => 'raw',
				'value'  => function (TmpFigureResult $figureResult) {
					return Html::a($figureResult->athlete->getFullName(), ['/competitions/athlete/view', 'id' => $figureResult->athleteId])
						. '<br>' . $figureResult->motorcycle->getFullTitle();
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
						'name'          => 'fine',
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
					$html = Html::a($figureResult->videoLink, $figureResult->videoLink, ['target' => '_blank']);
					if ($newClass = $figureResult->getNewTmpClass()) {
						$html .= '<br><span class="red">' . $newClass->title . '</span> ';
					}
					if ($newRecord = $figureResult->getNewRecord()) {
						$html .= '<br><span class="red">' . \common\models\FigureTime::$recordsTitle[$newRecord] . '</span>';
					}
					
					return $html;
				}
			],
			[
				'format' => 'raw',
				'value'  => function (TmpFigureResult $figureResult) {
					$html = '<div class = "pb-10">' . Html::a('<span class="fa fa-check"></span>',
							['/competitions/tmp-figures/approve', 'id' => $figureResult->id],
							['class'       => 'btn btn-my-style btn-green getRequestWithConfirm',
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
					$html = '<a href="#" class="btn btn-my-style btn-red cancelFigureResult"
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
					<?= Html::submitButton('Сохранить', ['class' => 'btn btn-lg btn-block btn-my-style btn-green']) ?>
                </div>
            </div>
			<?= Html::endForm() ?>
        </div>
    </div>
</div>