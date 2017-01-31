<?php
use common\models\RegionalGroup;
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use dosamigos\editable\Editable;

$group = new RegionalGroup();
?>

<div class="championship-form">
    <h3>Добавить группу региональных соревнований</h3>
    
    <div class="alert alert-info"><b>Внимание!</b> Не создавайте раздел, если аналогичный ему уже есть в списке -
    иначе пользователям станет сложнее найти нужные этапы</div>
	
	<?php $form = ActiveForm::begin(['options' => ['id' => 'newRegionalGroup']]); ?>

    <div class="row">
        <div class="col-md-10 col-sm-8">
			<?= $form->field($group, 'title')->textInput(['placeholder' => 'Название'])->label(false) ?>
        </div>
        <div class="col-md-2 col-sm-4">
            <div class="form-group">
				<?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
	
	<?php ActiveForm::end(); ?>
</div>

<?php
$i = 1;
?>
<table class="table">
	<?php foreach (RegionalGroup::find()->all() as $regionalGroup) { ?>
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
    