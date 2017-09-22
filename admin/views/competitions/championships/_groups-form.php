<?php
use common\models\RegionalGroup;
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use dosamigos\editable\Editable;

$group = new RegionalGroup();
?>

<div id="createGroup"></div>

<div class="championship-form">
    <h3>Добавить группу региональных соревнований</h3>

    <div class="alert required-alert-info"><b>Внимание!</b> Не создавайте раздел, если аналогичный ему уже есть в списке -
        иначе пользователям будет затруднительно найти нужный этап
    </div>

    <div class="alert help-alert alert-info">
        <div class="text-right">
            <span class="fa fa-remove closeHintBtn"></span>
        </div>
        Региональный раздел нужен для группировки чемпионатов по годам. Название может быть любым, напр. "Кубок
        &#60;вашего региона&#62;".
        Постарайтесь сделать название максимально понятным, чтобы при поиске человек понимал, что это кубок именно
        вашего региона.
    </div>
	
	<?php $form = ActiveForm::begin(['options' => ['id' => 'newRegionalGroup']]); ?>

    <div class="row">
        <div class="col-md-10 col-sm-8">
			<?= $form->field($group, 'title')->textInput(['placeholder' => 'Название'])->label(false) ?>
        </div>
        <div class="col-md-2 col-sm-4">
            <div class="form-group">
				<?= Html::submitButton('Добавить', ['class' => 'btn btn-my-style btn-green']) ?>
            </div>
        </div>
    </div>
	
	<?php ActiveForm::end(); ?>
</div>

<?php
$i = 1;
?>
<table class="table">
	<?php foreach (RegionalGroup::find()->orderBy(['title' => SORT_ASC])->all() as $regionalGroup) { ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= Editable::widget([
					'name'          => 'title',
					'value'         => $regionalGroup->title,
					'url'           => 'update-group',
					'type'          => 'text',
					'mode'          => 'inline',
					'clientOptions' => [
						'pk'        => $regionalGroup->id,
						'value'     => $regionalGroup->title,
						'placement' => 'right',
					]
				]); ?></td>
        </tr>
	<?php } ?>
</table>
    