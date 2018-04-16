<?php
/**
 * @var \yii\web\View            $this
 * @var \common\models\AssocNews $model
 */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\bootstrap\Html;
?>

<h2><?= $model->title ?></h2>

<div class="z-100">
	<div class="detail-news">
		<div class="item">
			<div class="preview_text">
				<?= $model->fullText ? $model->fullText : $model->previewText ?>
			</div>
			<div class="date text-left"><?= \Yii::$app->formatter->asDate($model->datePublish, "dd.MM.Y") ?></div>
		</div>
	</div>
</div>