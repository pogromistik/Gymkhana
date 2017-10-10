<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\NoticesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $success integer */
/* @var $model \common\models\Notice */

$this->title = 'Уведомления для зарегистрировавшихся пользователей';
$length = 255;
?>
<div class="notice-index">
	<?php if ($success) { ?>
        <div class="alert alert-success">
            Уведомление успешно отправлено
        </div>
	<?php } ?>
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'athleteId')->widget(Select2::classname(), [
		'name'    => 'kv-type-01',
		'data'    => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function (\common\models\Athlete $item) {
			return $item->lastName . ' ' . $item->firstName . ' (' . $item->city->title . ')';
		}),
		'options' => [
			'placeholder' => 'Выберите спортсмена...',
			'id'          => 'athlete-id',
		],
	]) ?>
	
	<?= $form->field($model, 'text',
		['inputTemplate' => '<div class="input-with-description">{input}</div><div class="text-right color-green" id="length">осталось символов: ' . $length . '</div>'])
		->textarea(['rows'        => 3,
		            'placeholder' => 'Текст уведомления, обязательное поле',
		            'id'          => 'smallText']) ?>
	
	<?= $form->field($model, 'link')->textInput(['maxlength' => 255, 'placeholder' => 'ссылка на подробную информацию']) ?>

    <div class="form-group">
		<?= Html::submitButton('Отправить', ['class' => 'btn btn-my-style btn-green']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

    <div class="text-right">
        <small>зеленым отмечены прочитанные уведомления</small>
    </div>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'rowOptions'   => function (\common\models\Notice $notice) {
			return ['class' => 'is-delivery-' . $notice->status];
		},
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'athleteId',
				'format'    => 'raw',
				'value'     => function (\common\models\Notice $notice) {
					return $notice->athlete ? $notice->athlete->getFullName() : null;
				}
			],
			'text',
			'link',
			[
				'attribute' => 'dateAdded',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (\common\models\Notice $notice) {
					return date('d.m.Y, H:i', $notice->dateAdded);
				}
			],
		],
	]); ?>
</div>
