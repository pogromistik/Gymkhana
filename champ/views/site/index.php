<?php
/**
 * @var \yii\web\View              $this
 * @var \common\models\AssocNews[] $news
 * @var \yii\data\Pagination       $pagination
 */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\bootstrap\Html;

?>
<h2><?= \Yii::t('app', 'Новости') ?></h2>
<div class="z-100">
    <div class="news">
		<?php foreach ($news as $item) { ?>
            <div class="item">
				<?php if ($item->title) { ?>
                    <div class="title-with-bg">
						<?= $item->title ?>
                    </div>
                    <div class="date"><?= \Yii::$app->formatter->asDate($item->datePublish, "dd.MM.Y") ?></div>
				<?php } else { ?>
                    <div class="title-with-bg date"><?= \Yii::$app->formatter->asDate($item->datePublish, "dd.MM.Y") ?></div>
				<?php } ?>
                <div class="preview_text">
					<?= $item->previewText ?>
                </div>
				<?php if ($item->link || $item->fullText) {
					if ($item->link) {
						$link = $item->link;
						$target = '_blank';
					} else {
						$link = Url::to(['/site/news', 'id' => $item->id]);
						$target = '_self';
					}
					?>
                    <div class="text-left">
						<?= Html::a(\Yii::t('app', 'Читать далее') . '...', $link, ['target' => $target]) ?>
                    </div>
				<?php } ?>
            </div>
		<?php } ?>
    </div>

    <div class="text-left">
		<?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>
</div>