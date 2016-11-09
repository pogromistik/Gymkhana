<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $form yii\widgets\ActiveForm */

$picture = new \common\models\helpers\Files();
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'parentId')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Page::getParents($model->id), 'id', 'title'), [
        'prompt' => 'Отсутствует'
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\Page::$statusesTitle) ?>

    <?= $form->field($model, 'showInMenu')->dropDownList(\common\models\Page::$showTitles) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'text')->widget(CKEditor::className(), [
        'options' => ['id' => 'newBlock'],
        'preset'  => 'custom',

    ]) ?>

    <div class="row pb-10">
        <div class="col-sm-12">
            <b>Заглавная картинка</b>
        </div>
        <?php if (!$model->isNewRecord && $model->pictureUrl) { ?>
            <div class="col-sm-1">
                <?= Html::img(\Yii::getAlias('@filesView').$model->pictureUrl) ?>
            </div>
        <?php } ?>
        <div class="col-sm-11">
            <?= $form->field($picture, 'picture')->fileInput(['multiple' => false, 'accept' => 'image/*'])->label(false) ?>
        </div>
    </div>

    <?= $form->field($model, 'pictureText')->textInput() ?>

    <?= $form->field($model, 'url')->textInput() ?>

    <?= $form->field($model, 'layoutId')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Layout::find()->all(), 'id', 'title')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
