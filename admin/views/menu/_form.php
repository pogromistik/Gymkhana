<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\GroupMenu;
use common\models\Page;

/* @var $this yii\web\View */
/* @var $model common\models\MenuItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alert alert-info">
    Пункт меню может представлять собой ссылку на страницу сайта или ссылку на любой другой ресурс.<br>
    1. Пункт меню ведёт на страницу сайта. В этом случае необходимо выбрать нужную страницу. По умолчанию в меню будет отображаться
    название этой страницы. Если необходим другой заголовок - необходимо заполнить поле "Название". Поле "ссылка" заполнять не надо.<br>
    2. Пункт меню ведёт на сторонний ресурс. В этом случае необходимо указать название и полную ссылку, включая "http://". Поле "Страница" трогать не нужно.<br>
    В обоих случаях поля "сортировка" и "группа" заполняются по желанию
</div>

<div class="menu-item-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'groupsMenuId')->dropDownList(ArrayHelper::map(GroupMenu::find()->all(), 'id', 'title'), ['prompt' => 'Выберите группу меню']) ?>
	
	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'sort')->textInput() ?>
	
	<?= $form->field($model, 'pageId')->dropDownList(
		ArrayHelper::map(
			\common\models\Page::find()->where(['status' => Page::STATUS_ACTIVE])->andWhere(['parentId' => null])->all(),
			'id',
			'title'),
		['prompt' => 'Выберите страницу']) ?>
	
	<?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
