<?php
/**
 * @var \common\models\Album   $album
 * @var \common\models\Album[] $otherAlbums
 */
use yii\bootstrap\Html;

?>

<div id="content" class="container">
    <div class="album-view">
        <div class="album-title text-center">
            <h3><?= $album->title ?></h3>
			<?php if ($album->description) { ?>
                <small><?= $album->description ?></small>
			<?php } ?>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="fotorama" data-nav="thumbs" data-allowfullscreen="true" data-width="100%">
					<?php foreach ($album->getPhotos() as $photo) { ?>
						<?= Html::img(Yii::getAlias('@filesView') . '/' . $album->folder . '/' . $photo) ?>
					<?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="al-bg-none">
    <a href="/photogallery/<?= $album->year->year ?>">выбрать другой альбом</a><br>
    <?php foreach ($otherAlbums as $otherAlbum) { ?>
        <a href="/photogallery/<?= $album->year->year ?>/<?= $otherAlbum->id ?>" class="<?= ($otherAlbum->id == $album->id) ? 'active' : null ?>">
            <?= $otherAlbum->title ?>
        </a>
        <br>
    <?php } ?>
</div>

