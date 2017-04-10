<?php

use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\AssocNews */
/* @var $form yii\widgets\ActiveForm */
$length = 255;
if ($model->previewText) {
	$length -= mb_strlen($model->previewText, 'UTF-8');
}
?>

<div class="assoc-news-form">
    <div class="alert alert-info">
        <ul>
            <li>
                <b>При добавлении изображения обязательно оставьте пустыми поля "ширина" и "высота" или хотя бы поле "высота"</b>
            </li>
            <li>Для создания новости необходимо заполнить поле "Короткий текст". Этот текст будет отображаться на
                главной странице.
            </li>
            <li>Если у новости должна быть подробная страница - заполните поле "Подробный текст". Тогда на главной
                странице у новости появится
                ссылка "читать далее", ведущая на подробную страницу.
            </li>
            <li>Если у новости должна быть кнопка "читать далее", ведущая не на её подробное описание, а на любую другую
                страницу -
                заполните поле "ссылка" (ссылку указывать полностью, включая http://)
            </li>
            <li>Дата публикации видна пользователям. По умолчанию это текущая дата. Сортируются новости по этой дате.
                Отображаться новость будет начиная с дня публикации.
            </li>
            <li>"Закрепить сверху" - если отметить этот пункт, новость всегда будет отображаться первой. Если таких
                новостей несколько -
                все они будут наверху страницы, но между собой будут сортироваться по дате публикации.
            </li>
        </ul>
    </div>
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'title')->textInput(['placeholder' => 'заголовок новости, не обязателен']) ?>
	
	<?= $form->field($model, 'previewText',
		['inputTemplate' => '<div class="input-with-description">{input}</div><div class="text-left color-green" id="length">осталось символов: ' . $length . '</div>'])->textarea(['rows'        => 3,
	                                                                                                                                                                                 'placeholder' => 'краткий текст, обязательное поле',
	                                                                                                                                                                                 'id'          => 'smallText']) ?>
	
	<?= $form->field($model, 'fullText')->widget(CKEditor::className(), [
		'preset' => 'full'
	]) ?>
	
	<?= $form->field($model, 'link')->textInput(['placeholder' => 'сторонняя ссылка, не обязательна']) ?>
	
	<?= $form->field($model, 'datePublishHuman',
		['inputTemplate' => '<div class="input-with-description"><div class="text">Время публикации 00:00 по GMT +5 (Челябинск)</div>{input}</div>'])->widget(DatePicker::classname(), [
		'options'       => ['placeholder' => 'Введите дату публикации'],
		'removeButton'  => false,
		'language'      => 'ru',
		'pluginOptions' => [
			'autoclose' => true,
			'format'    => 'dd.mm.yyyy',
		]
	]) ?>
	
    <?php if (\Yii::$app->user->can('admin')) { ?>
	<?= $form->field($model, 'secure')->checkbox() ?>
    <?php } ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
