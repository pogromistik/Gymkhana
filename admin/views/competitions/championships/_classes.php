<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use dosamigos\editable\Editable;

/**
 * @var \common\models\Championship $model
 */

$newClass = new \common\models\InternalClass();
$newClass->championshipId = $model->id;
?>

<?php if (!$model->useCheScheme) { ?>
    <div class="alert help-alert alert-info">
        <div class="text-right">
            <span class="fa fa-remove closeHintBtn"></span>
        </div>
        Обратите внимание - классы награждения не будут присваиваться спортсменам автоматически. Вам необходимо будет
        самостоятельно проставлять их на странице со списком участников этапа.
    </div>
	<?php $form = ActiveForm::begin(['action' => '/competitions/championships/add-class', 'options' => ['id' => 'ajaxForm']]); ?>
	<?= $form->field($newClass, 'championshipId')->hiddenInput()->label(false)->error(false) ?>
    <br>
    <div class="row">
        <div class="col-md-5 col-sm-12">
			<?= $form->field($newClass, 'title')->textInput(['placeholder' => 'Название'])->label(false) ?>
        </div>
        <div class="col-md-5 col-sm-12">
			<?= $form->field($newClass, 'description')->textarea(['rows' => 1, 'placeholder' => 'Описание'])->label(false) ?>
        </div>
        <div class="col-md-2 col-sm-12">
            <div class="form-group">
				<?= Html::submitButton('Добавить', ['class' => 'btn btn-my-style btn-green']) ?>
            </div>
        </div>
    </div>
	<?php ActiveForm::end(); ?>
<?php } ?>

<?php if ($internalClasses = $model->internalClasses) { ?>
	<?php if ($model->useCheScheme) { ?>
        <h4>Вы
            используете <?= Html::a('Челябинскую схему', ['/competitions/help/che-scheme'], ['target' => '_blank']) ?>
            для награждения</h4>
        <table class="table">
			<?php foreach ($internalClasses as $class) { ?>
                <tr>
                    <td><?= $class->title ?></td>
                    <td><?= $class->description ?></td>
                </tr>
			<?php } ?>
        </table>
	<?php } else { ?>
        <table class="table">
			<?php foreach ($internalClasses as $class) { ?>
                <tr>
                    <td><?= Editable::widget([
							'name'          => 'title',
							'value'         => $class->title,
							'url'           => 'update-class',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $class->id,
								'value'     => $class->title,
								'placement' => 'right',
							]
						]) ?></td>
                    <td><?= Editable::widget([
							'name'          => 'description',
							'value'         => $class->description,
							'url'           => 'update-class',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $class->id,
								'value'     => $class->description,
								'placement' => 'right',
							]
						]) ?></td>
                    <td>
						<?php
						if ($class->status) {
							echo Html::a('<span class="fa fa-remove"></span>', ['change-status', 'id' => $class->id], [
								'class'       => 'btn btn-my-style btn-red change-status',
								'data-action' => '/competitions/championships/change-class-status',
								'data-id'     => $class->id,
								'data-status' => \common\models\InternalClass::STATUS_INACTIVE,
								'title'       => 'Удалить',
							]);
						} else {
							echo Html::a('<span class="fa fa-check"></span>', ['change-status', 'id' => $class->id], [
								'class'       => 'btn btn-my-style btn-boggy change-status',
								'title'       => 'Вернуть',
								'data-action' => '/competitions/championships/change-class-status',
								'data-id'     => $class->id,
								'data-status' => \common\models\InternalClass::STATUS_ACTIVE,
							]);
						}
						?>
                    </td>
                </tr>
			<?php } ?>
        </table>
	<?php } ?>
<?php } ?>
