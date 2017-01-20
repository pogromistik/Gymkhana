<?php
/**
 * @var \common\models\Track $track
 */
use yii\bootstrap\Html;

?>

<div id="content" class="tracks">
	<?php foreach ($data['tracks'] as $track) { ?>
        <div class="item">
            <div class="picture">
		        <?= Html::img(\Yii::getAlias('@filesView') . '/' . $track->photoPath, [
			        'alt'   => $this->context->pageTitle . ': ' . $track->title,
			        'title' => $this->context->pageTitle . ': ' . $track->title
		        ]) ?>
            </div>
            <div class="info">
                <div class="track-text">
                    <div class="block text-center">
                        <div class="my-button text-center">
					        <?= $track->title ?>
                            <div class="my-button-border-bottom"></div>
                        </div>
                    </div>
                    <div class="description">
				        <?= $track->description ?>
                    </div>

                    <div class="block link">
                        <div class="my-button text-center">
	                        <?= Html::a('скачать', ['site/download', 'id' => $track->documentId]) ?>
                            <div class="my-button-border-top"></div>
                            <div class="my-button-border-bottom"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
	<?php } ?>
</div>
