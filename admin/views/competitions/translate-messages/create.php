<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TranslateMessageSource */

$this->title = 'Добавить сообщение';
$this->params['breadcrumbs'][] = ['label' => 'Перевод', 'url' => ['/competitions/translate-messages/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-create">
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
