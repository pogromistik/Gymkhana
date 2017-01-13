<?php
/**
 * @var \common\models\AboutBlock $block
 */
use yii\bootstrap\Html;

?>

<div id="content" class="project">
	<?php foreach ($data['blocks'] as $block) {
		?>
        <div class="one_project">
			<?php
			$pictures = $block->aboutSliders;
			if ($pictures) {
				?>
                <div class="slider">
                    <div class="owl-slider">
						<?php
						
						foreach ($pictures as $picture) {
							$title = 'Мотоджимхана Челябинск: ' . $block->sliderText ? $block->sliderText : 'о проекте';
							?>
                            <div class="item">
								<?= Html::img(\Yii::getAlias('@filesView') . $picture->picture, [
									'alt'   => $title,
									'title' => $title
								]) ?>
                            </div>
						<?php } ?>
                    </div>
					<?php if ($block->sliderText) { ?>
                        <div class="slider_text">
							<?= $block->sliderText ?>
                        </div>
					<?php } ?>
                </div>
				<?php
			}
			?>
			<?php if ($block->text) { ?>
                <div class="container">
                    <div class="p-text light-color">
						<?= $block->text ?>
                    </div>
                </div>
			<?php } ?>
        </div>
		<?php
	}
	?>
</div>
