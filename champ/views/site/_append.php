<?php
use yii\bootstrap\Html;

/**
 * @var int $i
 */
?>

<div class="col-sm-6 col-xs-12">
    <div class="form-group field-tmpathlete-mark has-success">
        <label class="control-label" for="tmpathlete-mark">Марка</label>
		<?= Html::input('text', 'mark[' . $i . ']', null, ['class' => 'form-control', 'placeholder' => 'Марка, напр. kawasaki']) ?>
    </div>
</div>
<div class="col-sm-6 col-xs-12">
    <div class="form-group field-tmpathlete-model has-success">
        <label class="control-label" for="tmpathlete-model">Модель</label>
		<?= Html::input('text', 'model[' . $i . ']', null, ['class' => 'form-control', 'placeholder' => 'Модель, напр. ER6-F']) ?>
    </div>
</div>