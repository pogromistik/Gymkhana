<?php

use dosamigos\tinymce\TinyMce;
use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $news common\models\AssocNews */
/* @var $form yii\widgets\ActiveForm */
?>
<h1>Создание новости</h1>
<div class="assoc-news-form">
    <div class="alert help-alert alert-info">
        <b>Если вы хотите вставить слайдер из изображений:</b><br>
        Слайдер запустится, если на странице будет код вида:<br>
        &lt;ul class="news-carousel"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;&lt;img src="ссылка на изображение"&gt;&lt;/li&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;&lt;img src="ссылка на изображение"&gt;&lt;/li&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;...<br>
        &lt;/ul&gt;<br>
        Для этого сперва необходимо добавить ваши изображения.<br>
        Нажмите на кнопку вставки изображения в панели (<span class="fa fa-image"></span>), вставьте ссылку на
        изображение, нажмите "ок".<br>
        Добавите таким образом все ваши изображения, после чего нажмите "Вид"&rarr;"Исходный код" в панели редактирования
        текста. Найдите то место, где расположены изображения. Они выглядят так:
        &lt;img src="ссылка на изображение"&gt;&lt;/li&gt;<br>
        Вставьте &lt;ul class="news-carousel"&gt; перед первым изображением, &lt;/ul&gt; - после последнего, а
        каждое изображение заключите в &lt;li&gt;&lt;/li&gt;.<br>
    </div>
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($news, 'title')->textInput(['placeholder' => 'заголовок новости, не обязателен']) ?>
	
	<?= $form->field($news, 'previewText')->textarea(['rows'        => 3,
	                                                  'placeholder' => 'краткий текст, обязательное поле',
	                                                  'id'          => 'smallText']) ?>
	<?= $form->field($news, 'fullText')->widget(TinyMce::className(), [
		'options'       => ['rows' => 6],
		'language'      => 'ru',
		'clientOptions' => [
			'plugins'       => [
				"advlist autolink lists link charmap hr preview pagebreak",
				"searchreplace wordcount textcolor visualblocks visualchars code fullscreen nonbreaking",
				"save insertdatetime media table contextmenu template paste image"
			],
			'toolbar'       => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | responsivefilemanager link image media",
			'valid_styles'  => "color text-align font-weight font-size",
			'valid_classes' => "news-carousel"
		]
	]); ?>
	
	<?= $form->field($news, 'link')->textInput(['placeholder' => 'сторонняя ссылка, не обязательна']) ?>

    <div class="form-group">
		<?= Html::submitButton($news->isNewRecord ? 'Добавить' : 'Сохранить',
			['class' => $news->isNewRecord ? 'btn btn-my-style btn-green' : 'btn btn-my-style btn-blue']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
