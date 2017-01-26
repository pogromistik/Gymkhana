<?php

use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\AssocNews */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assoc-news-form">
    
    <div class="alert alert-info">
        <ul>
            <li>Для создания новости необходимо заполнить поле "Короткий текст". Этот текст будет отображаться на главной странице.</li>
            <li>Если у новости должна быть подробная страница - заполните поле "Подробный текст". Тогда на главной странице у новости появится
                ссылка "читать далее", ведущая на подробную страницу.</li>
            <li>Если у новости должна быть кнопка "читать далее", ведущая не на её подробное описание, а на любую другую страницу -
            заполните поле "ссылка" (ссылку указывать полностью, включая http://)</li>
            <li>Дата публикации видна пользователям. По умолчанию это текущая дата. Сортируются новости по этой дате.
            Отображаться новость будет начиная с дня публикации.</li>
            <li>"Закрепить сверху" - если отметить этот пункт, новость всегда будет отображаться первой. Если таких новостей несколько -
            все они будут наверху страницы, но между собой будут сортироваться по дате публикации.</li>
        </ul>
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['placeholder' => 'заголовок новости, не обязателен']) ?>

    <?= $form->field($model, 'previewText')->textarea(['rows' => 3, 'placeholder' => 'краткий текст, обязательное поле']) ?>

	<?= $form->field($model, 'fullText')->widget(CKEditor::className(), [
		'preset'  => 'advent'
	]) ?>

    <?= $form->field($model, 'link')->textInput(['placeholder' => 'сторонняя ссылка, не обязательна']) ?>
	
	<?= $form->field($model, 'datePublishHuman')->widget(DatePicker::classname(), [
		'options'       => ['placeholder' => 'Введите дату публикации'],
		'removeButton'  => false,
		'language'      => 'ru',
		'pluginOptions' => [
			'autoclose' => true,
			'format'    => 'dd.mm.yyyy',
		]
	]) ?>
	
	<?= $form->field($model, 'secure')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
