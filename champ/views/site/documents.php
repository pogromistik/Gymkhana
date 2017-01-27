<?php
/**
 * @var \yii\web\View                    $this
 * @var \common\models\DocumentSection[] $sections
 */
/* @var $this yii\web\View */

use yii\bootstrap\Html;

?>


<div class="container">
    <div class="list">
		<?php foreach ($sections as $section) {
			$files = $section->files;
			?>
            <div class="item">
                <div class="toggle">
                    <div class="background"></div>
                    <div class="title">
						<?= $section->title ?>
                    </div>
					<?php if ($files) { ?>
                        <div class="info">
                            <ul>
								<?php foreach ($files as $file) { ?>
                                <li><?= Html::a($file->title, ['/base/download', 'id' => $file->id]) ?></li >
                            <?php } ?>
                            </ul>
                        </div>
					<?php } ?>
                </div>
            </div>
		<?php } ?>
    </div>
</div>