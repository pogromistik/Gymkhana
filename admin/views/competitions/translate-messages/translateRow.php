<?php
/**
 * @var TranslateMessageSource $model
 */

use common\models\TranslateMessageSource;

$rowspan = count(\common\models\TranslateMessage::$languagesTitle);
?>

<tr>
	<td rowspan="<?= count(\common\models\TranslateMessage::$languagesTitle) ?>"><?= $model->message ?></td>
	<?php $i = 0;
	foreach (\common\models\TranslateMessage::$languagesTitle as $language => $title) { ?>
<?php if ($i++ > 1) { ?><tr><?php } ?>
<?php
$translateForm = $model->getMessageForm($language);
$form = \yii\bootstrap\ActiveForm::begin([
	'id'      => 'message-form-' . $model->id . '-' . $language,
	'options' => [
		'class' => 'TranslateMessagesForm'
	]

]);
?>
<td>
	<?= $form->field($translateForm, 'id')->label(false)->hiddenInput()->error(false) ?>
	<?= $form->field($translateForm, 'language')->label(false)->hiddenInput()->error(false) ?>
	<?= $form->field($translateForm,
		'language')->dropDownList(\common\models\TranslateMessage::$languagesTitle, ['disabled' => true])->label(false) ?>
</td>
<td class="translate-row">
	<?= $form->field($translateForm,
		'translation')->textarea(['rows' => 1])->label(false) ?>
</td>
<td>
	<button type="submit" class="btn btn-primary btn-circle fa fa-save" title="Сохранить"></button>
</td>
<?php $form->end() ?>
</tr>
<?php } ?>
