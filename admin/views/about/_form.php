<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\AboutBlock */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="about-block-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'text')->widget(CKEditor::className(), [
        'options' => ['id' => 'newBlock'],
        'preset'  => 'basic',

    ]) ?>

    <?= $form->field($model, 'sliderText')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slider[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <?php if (!$model->isNewRecord) { ?>
        <table class="table">
            <thead>
            <tr>
                <th>Изображение</th>
                <th>Сортировка</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($model->aboutSliders as $slider) {
                ?>
                <tr>
                    <td>
	                    <?= Html::img(Yii::getAlias('@filesView') . '/' . $slider->picture) ?>
                    </td>
                    <td><?= \dosamigos\editable\Editable::widget([
                            'name'          => 'sort',
                            'value'         => $slider->sort,
                            'url'           => '/about/update-slider',
                            'type'          => 'text',
                            'mode'          => 'inline',
                            'clientOptions' => [
                                'pk'        => $slider->id,
                                'value'     => $slider->sort,
                                'placement' => 'right',
                            ]
                        ])
                        ?></td>
                    <td>
                        <?= Html::a('Удалить', ['/about/delete-slider', 'id' => $slider->id, 'modelId' => $model->id],
                            ['data' => [
                                'confirm' => 'Вы уверены, что хотите удалить это изображение?',
                                'method'  => 'post',
                            ]]) ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
