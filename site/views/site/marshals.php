<?php
/**
 * @var \common\models\Marshal $marshal
 */
use yii\bootstrap\Html;

?>

<div id="content" class="marshals">
    <div class="k-gif"></div>
	<?php foreach ($data['marshals'] as $id => $marshal) { ?>
        <div class="container marshal">
            <div class="title text-center">
                <h4><?= $marshal->name ?></h4>
                <small><?= $marshal->post ?></small>
            </div>

            <div class="photo">
				<?= Html::img(\Yii::getAlias('@filesView') . '/' . $marshal->photo, [
					'alt'   => $this->context->pageTitle . ': ' . $marshal->name,
					'title' => $this->context->pageTitle . ': ' . $marshal->name
				]) ?>
            </div>

            <div class="row big-show">
                <div class="col-md-3 col-sm-6 item">
                    <div class="my-button">
                        <div class="my-button-border-right"></div>
                        <p><?= $marshal->text1 ?></p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 item">
                    <div class="my-button">
                        <div class="my-button-border-right"></div>
                        <p class="text-center">
							<?= $marshal->motorcycle ?>
							<?= Html::img(\Yii::getAlias('@filesView') . '/' . $marshal->motorcyclePhoto, [
								'alt'   => $marshal->motorcycle,
								'title' => $marshal->motorcycle,
								'id'    => ($id == 0) ? '37' : $id
							]) ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 item">
                    <div class="my-button">
                        <div class="my-button-border-right"></div>
                        <p><?= $marshal->text2 ?></p>
                    </div>

                </div>
                <div class="col-md-3 col-sm-6 item">
                    <p><?= $marshal->text3 ?></p>
                    <div class="link text-center">
                        <div class="my-button">
                            <div class="my-button-border-top"></div>
                            <a href="<?= $marshal->link ?>" target="_blank"><?= $marshal->link ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row big-hide">
                <div class="col-sm-12 item">
	                <?= $marshal->text1 ?>
                </div>
                <div class="col-sm-12 item">
	                <?= $marshal->text2 ?>
                </div>
                <div class="col-sm-12 item">
	                <?= $marshal->text3 ?>
                </div>
                <div class="col-sm-12 item motorcycle">
	                <?= $marshal->motorcycle ?>
		            <?= Html::img(\Yii::getAlias('@filesView') . '/' . $marshal->motorcyclePhoto, [
			            'alt'   => $marshal->motorcycle,
			            'title' => $marshal->motorcycle
		            ]) ?>
                </div>
                <div class="col-sm-12 item">
                    <a href="<?= $marshal->link ?>" target="_blank"><?= $marshal->link ?></a>
                </div>
            </div>
        </div>
	<?php } ?>
</div>


