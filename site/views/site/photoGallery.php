<?php
/**
 * @var \common\models\Year $year
 * @var \common\models\Page $page
 */
use yii\bootstrap\Html;
use yii\widgets\LinkPager;
?>

<div id="content" class="opacity-menu albums">
    <div class="row">
        <div class="col-md-6 hidden-sm hidden-xs z-1">
            <div class="full-logo">
                <img src="/img/logo_full_chb.png">
            </div>
        </div>

        <div class="col-md-offset-4 col-md-8 col-sm-12 all-news z-2">
			<?php foreach ($data['years'] as $year) { ?>
                <div class="item text-right">
                    <div class="album_link text-right">
                        <?= Html::a($year->year, '/photogallery/'.$year->year) ?>
                    </div>
                </div>
			<?php } ?>
        </div>
    </div>
    <div class="text-right">
		<?= LinkPager::widget(['pagination' => $data['pagination']]) ?>
    </div>
</div>