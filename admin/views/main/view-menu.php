<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Page;
use common\models\MainMenu;

/* @var $this yii\web\View */
/* @var $item common\models\MainMenu */
/* @var $form yii\widgets\ActiveForm */

$this->title = $item->isNewRecord ? 'Добавление меню для главной страницы' : 'Редактирование пункта "'.$item->title.'" для главной страницы';
$this->params['breadcrumbs'][] = ['label' => 'Главная страница', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-info">
	Пункт меню может представлять собой ссылку на страницу сайта или ссылку на любой другой ресурс.<br>
	1. Пункт меню ведёт на страницу сайта. В этом случае необходимо выбрать нужную страницу. По умолчанию в меню будет отображаться
	название этой страницы. Если необходим другой заголовок - необходимо заполнить поле "Название". Поле "ссылка" заполнять не надо.<br>
	2. Пункт меню ведёт на сторонний ресурс. В этом случае необходимо указать название и полную ссылку, включая "http://". Поле "Страница" трогать не нужно.<br>
	В обоих случаях поле "сортировка" заполняются по желанию, поле "тип" - обязательно для заполнения
</div>

<div class="menu-item-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($item, 'type')->dropDownList(MainMenu::$typesTitle, ['prompt' => 'Выберите тип меню']) ?>
	
	<?= $form->field($item, 'title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($item, 'sort')->textInput() ?>
	
	<?= $form->field($item, 'pageId')->dropDownList(
		ArrayHelper::map(
			\common\models\Page::find()->where(['status' => Page::STATUS_ACTIVE])->andWhere(['parentId' => null])->all(),
			'id',
			'title'),
		['prompt' => 'Выберите страницу']) ?>
	
	<?= $form->field($item, 'link')->textInput(['maxlength' => true]) ?>
	
	<div class="form-group">
		<?= Html::submitButton($item->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $item->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>
