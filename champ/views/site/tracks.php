<?php
/**
 * @var \yii\web\View $this
 * @var array         $data
 */
?>

<h1><?= $this->context->pageTitle ?></h1>

<div class="tracks-gallery">
    <div class="row">
		<?php foreach ($data as $date => $info) { ?>
			<?php foreach ($info['items'] as $item) { ?>
                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 item">
                    <div class="img">
						<?= yii\helpers\Html::a(\yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $item['photoPath']),
							\Yii::getAlias('@filesView') . '/' . $item['photoPath'],
                            ['data-fancybox' => 'gallery']) ?>
                    </div>
                    <div class="info">
						<?php if ($info['year']) { ?>
                            <div>Год: <?= $info['year'] ?></div>
                            <div>Лучшее время: <?= $item['bestTime'] ?></div>
                            <div>Класс спортсмена: <?= $item['class'] ?></div>
                            <div><?= \yii\helpers\Html::a('Страница этапа', $item['url']) ?></div>
						<?php } ?>
                    </div>
                </div>
			<?php } ?>
		<?php } ?>
    </div>
</div>
