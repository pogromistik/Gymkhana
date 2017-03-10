<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Notice */
/* @var $form yii\widgets\ActiveForm */
$length = 255;
?>

<div class="notice-form">
	
	<?php $form = ActiveForm::begin(); ?>

    <div class="form-group field-regionId">
        <label class="control-label" for="regionId">Регионы, всем спортсменам которых будут отправлены
            уведомления</label>
        <div class="input-with-description">
            <div class="text">
                Если вы хотите отправить уведомление вообще всем спортсменам - оставьте поле пустым.
            </div>
			<?= Select2::widget([
				'name'          => 'regionIds',
				'data'          => \common\models\Region::getAll(true),
				'options'       => [
					'placeholder' => 'Выберите регионы...',
					'multiple' => true
				],
				'pluginOptions' => [
					'tags' => true,
					'tokenSeparators' => [',', ' '],
				],
			]) ?>
        </div>
    </div>
	
	<?= $form->field($model, 'text',
		['inputTemplate' => '<div class="input-with-description">{input}</div><div class="text-right color-green" id="length">осталось символов: ' . $length . '</div>'])
		->textarea(['rows'        => 3,
		            'placeholder' => 'Текст уведомления, обязательное поле',
		            'id'          => 'smallText']) ?>
	
	<?= $form->field($model, 'link')->textInput(['maxlength' => 255, 'placeholder' => 'ссылка на подробную информацию']) ?>

    <div class="form-group">
		<?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
