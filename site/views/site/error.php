<?php
$this->title = 'Страница не найда';
?>
<div class="system-page">
	<h3>Страница не найдена</h3>
	<?= \yii\bootstrap\Html::img('/img/404.png', [
		'alt'   => 'Страница не найдена',
		'title' => 'Страница не найдена'
	]) ?>
</div>