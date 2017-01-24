<?php
use yii\bootstrap\Html;

$thanks = \common\models\HelpModel::$thanks;
$photo = null;
$word = null;
?>

<div id="content" class="big-show">
    <div class="row help_project">
		<?php $i = 0;
		while ($i++ < 12) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-sm-1 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
    </div>

    <div class="row help_project">
		<?php $i = 0;
		while ($i++ < 2) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-sm-1 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
		<?php $i = 0;
		?>
        <div class="col-sm-8 text">
            <div class="item">
				<?= $data['model']->text1 ?>
            </div>
        </div>
		<?php $i = 0;
		while ($i++ < 2) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-sm-1 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
    </div>

    <div class="row help_project">
		<?php $i = 0;
		while ($i++ < 12) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-sm-1 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
    </div>

    <div class="row help_project">
		<?php $i = 0;
		while ($i++ < 6) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-sm-1 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
        <div class="col-sm-4 text">
            <div class="item">
				<?= $data['model']->text2 ?>
            </div>
        </div>
		<?php $i = 0;
		while ($i++ < 2) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-sm-1 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
    </div>

    <div class="row help_project">
		<?php $i = 0;
		while ($i++ < 12) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-sm-1 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
    </div>

    <div class="row help_project">
		<?php $i = 0;
		while ($i++ < 4) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-sm-1 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
        <div class="col-sm-6 text">
            <div class="item card">
				<?= $data['card']->card ?><br>
				<?= $data['card']->cardInfo ?>
            </div>
        </div>
		<?php $i = 0;
		while ($i++ < 2) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-sm-1 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
    </div>

</div>

<div id="content" class="big-hide">
    <div class="row help_project">
		<?php $i = 0;
		while ($i++ < 4) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-xs-3 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
    </div>

    <div class="row help_project">
		<?php $i = 0;
		?>
        <div class="col-xs-12 text">
            <div class="item">
				<?= $data['model']->text1 ?>
            </div>
        </div>
    </div>

    <div class="row help_project">
        <div class="col-xs-12 text">
            <div class="item">
				<?= $data['model']->text2 ?>
            </div>
        </div>
    </div>

    <div class="row help_project">
        <div class="col-xs-12 text">
            <div class="item card">
				<?= $data['card']->card ?><br>
				<?= $data['card']->cardInfo ?>
            </div>
        </div>
    </div>

    <div class="row help_project">
		<?php $i = 0;
		while ($i++ < 4) {
			$photo = next($data['photos']);
			if (!$photo) {
				$photo = reset($data['photos']);
			}
			$word = next($thanks);
			if (!$word) {
				$word = reset($thanks);
			}
			?>
            <div class="col-xs-3 sliphover-active">
				<?= Html::img(Yii::getAlias('@filesView') . '/' . $data['model']->imgFolder . '/' . $photo, [
					'alt'   => $word,
					'title' => $word,
					'class' => "slip"
				]) ?>
            </div>
		<?php } ?>
    </div>

</div>