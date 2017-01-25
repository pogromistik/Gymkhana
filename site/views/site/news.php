<?php
/**
 * @var \common\models\News   $news
 * @var \common\models\Page[] $oldNews
 */
use yii\bootstrap\Html;

$news = $data['page']->news;
$oldNews = $data['oldNews'];
$blocks = $news->newsBlock;
?>

<?php
$this->registerJsFile('http://vk.com/js/api/openapi.js', ['position' => yii\web\View::POS_HEAD]);
?>

<div id="content" class="news">
	
	<?php foreach ($blocks as $block) { ?>
        <div class="item">
			<?php if ($slider = $block->newsSliders) { ?>
                <div class="slider">
                    <div class="owl-slider">
						<?php foreach ($slider as $picture) { ?>
                            <div class="item">
								<?= Html::img(Yii::getAlias('@filesView') . $picture->picture, [
									'alt'   => $this->context->pageTitle,
									'title' => $this->context->pageTitle
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
			<?php } ?>

            <div class="container">
                <div class="p-text light-color">
					<?= $block->text ?>
                </div>
                <div class="p-text light-color text-right">
                    <?= date("d.m.Y", $news->datePublish) ?>
                </div>
            </div>
        </div>
	<?php } ?>
</div>

<!-- предыдущие новости -->
<?php if ($oldNews) { ?>
    <div class="row news_list sliphover-active">
		<?php foreach ($oldNews as $link) { ?>
            <div class="col-xs-2 item">
                <a href="/<?= $link->url ?>">
                    <figure class="images">
						<?= Html::img(Yii::getAlias('@filesView') . $link->news->previewImage, [
							'alt'   => $link->title,
							'title' => $link->title,
							'class' => "slip"
						]) ?>
                    </figure>
                </a>
            </div>
		<?php } ?>
    </div>
<?php } ?>

<div class="vk_widget container pt-20 pb-20">
    <script type="text/javascript">
        VK.init({apiId: 5241684, onlyWidgets: true});
    </script>

    <!-- Put this div tag to the place, where the Comments block will be -->
    <div id="vk_comments"></div>
    <script type="text/javascript">
        VK.Widgets.Comments("vk_comments", {limit: 15, attach: "*"});
    </script>
</div>
