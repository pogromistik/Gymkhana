<?php
/**
 * @var \yii\web\View                    $this
 * @var \common\models\DocumentSection[] $sections
 */
/* @var $this yii\web\View */

use yii\bootstrap\Html;

?>
<h2><?= \Yii::t('app', 'Документы') ?></h2>
<div class="documents">
    <div class="list">
		<?php foreach ($sections as $section) {
			$files = $section->actualFiles;
			?>
            <div class="item card-box">
                <div class="toggle">
                    <div class="background"></div>
                    <div class="title">
						<?= \Yii::t('app', $section->title) ?>
                    </div>
					<?php if ($files) { ?>
                        <div class="info">
                            <ul>
								<?php foreach ($files as $file) { ?>
                                    <li><?= Html::a($file->title, ['/base/download', 'id' => $file->id]) ?></li>
								<?php } ?>
                            </ul>
                        </div>
					<?php } ?>
                </div>
            </div>
		<?php } ?>
    </div>
</div>