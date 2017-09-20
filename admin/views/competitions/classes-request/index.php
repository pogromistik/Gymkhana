<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ClassesRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы на изменение класса';
?>
<div class="classes-request-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'athleteId',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\ClassesRequest $item) {
					$athlete = $item->athlete;
					$html = $athlete->getFullName();
					if ($athlete->athleteClass) {
						$html .= ', ' . $athlete->athleteClass->title;
					}
					$html .= '<br><small>' . $athlete->city->title . '</small>';
					
					return $html;
				}
			],
			[
				'attribute' => 'newClassId',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\ClassesRequest $item) {
					return $item->class->title;
				}
			],
			[
				'attribute' => 'comment',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\ClassesRequest $item) {
					return nl2br(htmlspecialchars($item->comment));
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\ClassesRequest $item) {
					$html = '<a href="#" class="btn btn-success processClassRequest" data-status=' . \common\models\ClassesRequest::STATUS_APPROVE . '
data-id = ' . $item->id . '
>
<span class="fa fa-check"></span>
</a>';
					
					return $html;
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\ClassesRequest $item) {
					$html = '<a href="#" class="btn btn-danger processClassRequest" data-status=' . \common\models\ClassesRequest::STATUS_CANCEL . '
data-id = ' . $item->id . '
>
<span class="fa fa-remove"></span>
</a>';
					
					return $html;
				}
			]
		],
	]); ?>
</div>

<div class="modal fade" id="processClassRequest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h3></h3></div>
			<?= Html::beginForm('', 'post', [
				'id' => 'processClassRequestForm',
			]) ?>
            <div class="modal-body">
                <h4></h4>
				<?= Html::hiddenInput('id', '', ['id' => 'id']) ?>
				<?= Html::hiddenInput('status', '', ['id' => 'status']) ?>
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
