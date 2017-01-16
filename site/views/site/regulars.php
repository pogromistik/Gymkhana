<?php
/**
 * @var \common\models\Regular $regular
 */
use yii\bootstrap\Html;

?>

<div id="content" class="regular show-pk opacity-menu">
    <div class="row">
        <div class="col-md-6 hidden-sm hidden-xs">
            <div class="full-logo">
				<?= Html::img('/img/logo_full_chb.png', [
					'alt'   => $this->context->pageTitle,
					'title' => $this->context->pageTitle
				]) ?>
                <h3><?= $this->context->pageTitle ?></h3>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 regulars">
            <table class="regular-table">
                <tr>
                    <td>
						<?php foreach ($data['regulars'][1] as $regular) { ?>
                            <div class="item">
								<?= $regular->text ?>
                            </div>
						<?php } ?>
                    </td>
                    <td>
                        <div class="row">
							<?php foreach ($data['regulars'][2] as $regular) { ?>
                                <div class="item">
									<?= $regular->text ?>
                                </div>
							<?php } ?>
                            <div class="regular_img">
                                <div class=".img img1">
									<?= Html::img('/img/img1.png', [
										'alt'   => $this->context->pageTitle,
										'title' => $this->context->pageTitle
									]) ?>
                                </div>
                                <div class=".img img2">
									<?= Html::img('/img/img2.jpg', [
										'alt'   => $this->context->pageTitle,
										'title' => $this->context->pageTitle
									]) ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
							<?php foreach ($data['regulars'][3] as $regular) { ?>
                                <div class="col-xs-6">
                                    <div class="item">
										<?= $regular->text ?>
                                    </div>
                                </div>
							<?php } ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
