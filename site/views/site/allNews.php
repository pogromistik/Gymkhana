<?php
/**
 * @var \common\models\News $news
 */
use yii\bootstrap\Html;
use yii\widgets\LinkPager;
?>

<div id = "content" class="opacity-menu">
	<div class="row">
		<div class="col-md-6 hidden-sm hidden-xs z-1">
			<div class="full-logo">
				<img src="/img/logo_full_chb.png" alt="Мотоджимхана Челябинск: новости" title="Мотоджимхана Челябинск: новости">
			</div>
		</div>
		
		<?php foreach ($data['news'] as $news) {
			$page = $news->page;
			?>
			<div class="col-md-offset-4 col-md-8 col-sm-12 all-news z-2">
				<div class="item text-right">
					<a href="/<?= $page->url ?>">
						<div class="row">
							<div class="col-md-offset-3 col-md-7 col-sm-9 col-sm-offset-0 col-xs-7 text-left preview-text">
								<h3><?= $news->title ?></h3>
								<?= $news->previewText ?>
							</div>
							<div class="col-md-2 col-sm-3 col-xs-5">
								<?= Html::img(\Yii::getAlias('@filesView') . $news->previewImage, [
									'alt'   => $news->title,
									'title' => $news->title,
								]) ?>
							</div>
						</div>
					</a>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="text-right">
		<?= LinkPager::widget(['pagination' => $data['pagination']]) ?>
	</div>
</div>
