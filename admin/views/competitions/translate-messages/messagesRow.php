<?php
/**
 * @var TranslateMessageSource $model
 */

use common\models\TranslateMessageSource;

?>

<tr class="order-row">
	<?php $form = \yii\bootstrap\ActiveForm::begin([
		'id'      => 'message-form-' . $model->id,
		'options' => [
			'class' => 'messageForTranslateForm'
		]
	
	]);
	?>
    <td><?= $form->field($model, 'id')->label(false)->hiddenInput()->error(false) ?>
		<?= $form->field($model, 'message')->label(false)->textInput() ?></td>
    <td><?= $form->field($model, 'comment')->label(false)->textarea(['rows' => 1]) ?></td>
	<td>
		<?= $form->field($model,
			'status')->dropDownList(TranslateMessageSource::$statusesTitle)->label(false) ?>
	</td>
	<td>
		<button type="submit" class="btn btn-primary btn-circle fa fa-save" title="Сохранить"></button>
		<?php $form->end() ?>
		<?php
		if ($model->status != TranslateMessageSource::STATUS_ACTIVE) {
		?>
		<span class="btn btn-success btn-circle fa fa-check" title="Подтвердить"
		      onclick="changeMessageStatus('<?= $model->id ?>')">
    <?php
    } else { ?>
			<span class="btn btn-danger btn-circle fa fa-remove" title="Отменить"
			      onclick="changeMessageStatus('<?= $model->id ?>')">
    <?php } ?>
                    </span>
	
	</td>
</tr>