<?php
/**
 * @var \common\models\Year    $year
 * @var \common\models\Album[] $albums
 */
use yii\bootstrap\Html;
?>

<div id="content" class="opacity-menu album">
    <div class="row">
        <div class="col-md-6 hidden-sm hidden-xs z-1">
            <div class="full-logo">
                <img src="/img/logo_full_chb.png">
                <h3>2014</h3>
            </div>
        </div>

        <div class="col-md-offset-6 col-md-6 col-sm-12 all-news z-2">
            <div class="row sliphover-active">
				<?php foreach ($albums as $album) { ?>
                    <div class="col-sm-4 col-xs-6">
                        <a href="/photogallery/<?= $year->year ?>/<?= $album->id ?>">
                            <figure class="images">
			                    <?= Html::img(Yii::getAlias('@filesView') . $album->cover, [
				                    'alt'   => $album->title,
				                    'title' => $album->title,
				                    'class' => "slip"
			                    ]) ?>
                            </figure>
                        </a>
                    </div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>
