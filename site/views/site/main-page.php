<?php
use yii\bootstrap\Html;

/**
 * @var \common\models\MainPhoto[] $slider
 * @var \common\models\MainPhoto[] $rightMenu
 * @var \common\models\MainPhoto[] $bottomMenu
 * @var \common\models\Link[]      $social
 * @var \common\models\News        $news
 * @var \common\models\MainMenu    $menuItem
 * @var \common\models\Year[]      $years
 */
?>

<div class="main-content-2" id="main-content">
    <!-- HEADER BANNER -->
    <section id="header-2" class="header-2" data-stellar-background-ratio="0.4">
        <div id="main-slider">
			<?php foreach ($slider as $slide) { ?>
                <div class="item">
					<?= Html::img(\Yii::getAlias('@filesView') . $slide->fileName, [
						'alt'   => 'Мотоджимхана Челябинск',
						'title' => 'Мотоджимхана Челябинск'
					]) ?>
                </div>
			<?php } ?>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="page-title-2">
                        <h1>moto gymkhana</h1>
                        <div class="page-title-small-parent">
                            <div class="page-title-small-2 page-title-small-style-2">Тяжела и неказиста жизнь простого
                                джимханиста
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- =========================
		END TOP BANNER
	============================== -->
    <!-- =========================
		OUR SERVICES
	============================== -->

    <section id="services" class="main-menu-content">
        <!-- end header -->
        <!-- start main nav -->
        <div class="menu">
            <div class="social">
				<?php foreach ($social as $item) { ?>
                    <a href="<?= $item->link ?>" title="<?= $item->title ?>" target="_blank"><i
                                class="fa <?= $item->class ?>"></i></a>
				<?php } ?>
            </div>
            <!-- end main nav -->
			<?php if ($menuItems['green']) {
				?>
                <nav id="main-nav">
                    <ul id="options" class="option-set clearfix" data-option-key="filter">
						<?php
						foreach ($menuItems['green'] as $menuItem) {
							$page = $menuItem->page;
							?>
                            <li><a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
									<?= $menuItem->title ? $menuItem->title : $page->title ?>
                                </a></li>
							<?php
						}
						?>
                    </ul>
                </nav>
				<?php
			}
			?>
        </div>
    </section>
    <!-- =========================
		END OUR SERVICES
	============================== -->

    <section id="bottom-menu-pk" class="main-menu-content">
        <div class="menu-bottom">
            <div class="row c-black">
                <div class="col-md-6 main_news">
                    <a href="/<?= $news->page->url ?>">
						<?= Html::img(Yii::getAlias('@filesView') . $news->previewImage, [
							'alt'   => $news->title,
							'title' => $news->title
						]) ?>
                        <div class="my-button">
                            <div class="my-button-top">Главная новость</div>
                        </div>
						<?php if ($news->previewText) { ?>
                            <div class="my-button">
                                <div class="my-button-bottom">
									<?= $news->previewText ?>
                                </div>
                            </div>
						<?php } ?>
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-6 height1">
                            <div class="element  col1-3 b-b">
								<?php if ($menuItem = reset($menuItems['animateSquare'])) {
									$page = $menuItem->page;
									$title = $menuItem->title ? $menuItem->title : $page->title;
									?>
                                    <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                        <figure class="images">
											<?= Html::img(\Yii::getAlias('@filesView') . reset($bottomMenu), [
												'alt'   => $title,
												'title' => $title,
												'class' => 'slip'
											]) ?>
                                        </figure>
                                    </a>
								<?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="element  col1-3 b-b b-l">
								<?php
								$next = next($menuItems['animateSquare']);
								$menuItem = $next ? $next : reset($menuItems['animateSquare']);
								if ($menuItem) {
									$page = $menuItem->page;
									$title = $menuItem->title ? $menuItem->title : $page->title;
									$next = next($bottomMenu);
									$folder = $next ? $next : reset($bottomMenu);
									?>
                                    <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                        <figure class="images">
											<?= Html::img(\Yii::getAlias('@filesView') . $folder, [
												'alt'   => $title,
												'title' => $title,
												'class' => 'slip'
											]) ?>
                                        </figure>
                                    </a>
								<?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="element  clearfix col1-3 grey">
                                <div class="icons team"></div>
								<?php if ($menuItem = reset($menuItems['graySquare'])) {
									$page = $menuItem->page;
									?>
                                    <h3><?= $menuItem->title ? $menuItem->title : $page->title ?></h3>
                                    <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>"
                                       data-title="Who?" class="splink">
                                        <div class="bottom">
                                            <p>Поехали!</p>
                                        </div>
                                    </a>
								<?php } ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="element  clearfix col1-3 b-l">
								<?php
								$next = next($menuItems['animateSquare']);
								$menuItem = $next ? $next : reset($menuItems['animateSquare']);
								if ($menuItem) {
									$page = $menuItem->page;
									$title = $menuItem->title ? $menuItem->title : $page->title;
									$next = next($bottomMenu);
									$folder = $next ? $next : reset($bottomMenu);
									?>
                                    <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                        <figure class="images">
											<?= Html::img(\Yii::getAlias('@filesView') . $folder, [
												'alt'   => $title,
												'title' => $title,
												'class' => 'slip'
											]) ?>
                                        </figure>
                                    </a>
								<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="element  clearfix col1-3">
                                    <div class="icons illustration"></div>
									<?php if ($menuItem = next($menuItems['graySquare'])) {
										$page = $menuItem->page;
										?>
                                        <h3><?= $menuItem->title ? $menuItem->title : $page->title ?></h3>
                                        <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>"
                                           data-title="Who?" class="splink">
                                            <div class="bottom">
                                                <p>Поехали!</p>
                                            </div>
                                        </a>
									<?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="element  clearfix col1-3">
									<?php
									$next = next($menuItems['animateSquare']);
									$menuItem = $next ? $next : reset($menuItems['animateSquare']);
									if ($menuItem) {
										$page = $menuItem->page;
										$title = $menuItem->title ? $menuItem->title : $page->title;
										$next = next($bottomMenu);
										$folder = $next ? $next : reset($bottomMenu);
										?>
                                        <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                            <figure class="images">
												<?= Html::img(\Yii::getAlias('@filesView') . $folder, [
													'alt'   => $title,
													'title' => $title,
													'class' => 'slip'
												]) ?>
                                            </figure>
                                        </a>
									<?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="element  clearfix col1-3">
									<?php
									$next = next($menuItems['animateSquare']);
									$menuItem = $next ? $next : reset($menuItems['animateSquare']);
									if ($menuItem) {
										$page = $menuItem->page;
										$title = $menuItem->title ? $menuItem->title : $page->title;
										$next = next($bottomMenu);
										$folder = $next ? $next : reset($bottomMenu);
										?>
                                        <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                            <figure class="images">
												<?= Html::img(\Yii::getAlias('@filesView') . $folder, [
													'alt'   => $title,
													'title' => $title,
													'class' => 'slip'
												]) ?>
                                            </figure>
                                        </a>
									<?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="element  clearfix col1-3 grey height0">
                                    <div class="icons network"></div>
									<?php if ($menuItem = next($menuItems['graySquare'])) {
										$page = $menuItem->page;
										?>
                                        <h3><?= $menuItem->title ? $menuItem->title : $page->title ?></h3>
                                        <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>"
                                           data-title="Who?" class="splink">
                                            <div class="bottom">
                                                <p>Поехали!</p>
                                            </div>
                                        </a>
									<?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12 col-sm-6">
                            <div class="element  clearfix col1-3 b-b">
								<?php
								$next = next($menuItems['animateSquare']);
								$menuItem = $next ? $next : reset($menuItems['animateSquare']);
								if ($menuItem) {
									$page = $menuItem->page;
									$title = $menuItem->title ? $menuItem->title : $page->title;
									$next = next($bottomMenu);
									$folder = $next ? $next : reset($bottomMenu);
									?>
                                    <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                        <figure class="images">
											<?= Html::img(\Yii::getAlias('@filesView') . $folder, [
												'alt'   => $title,
												'title' => $title,
												'class' => 'slip'
											]) ?>
                                        </figure>
                                    </a>
								<?php } ?>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6">
                            <div class="element  clearfix col1-3">
								<?php
								$menuItem = next($menuItems['animateSquare']) ? next($menuItems['animateSquare']) : reset($menuItems['animateSquare']);
								if ($menuItem) {
									$page = $menuItem->page;
									$title = $menuItem->title ? $menuItem->title : $page->title;
									$folder = next($bottomMenu) ? next($bottomMenu) : reset($bottomMenu);
									?>
                                    <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                        <figure class="images">
											<?= Html::img(\Yii::getAlias('@filesView') . $folder, [
												'alt'   => $title,
												'title' => $title,
												'class' => 'slip'
											]) ?>
                                        </figure>
                                    </a>
								<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="element  clearfix col2-3 home grey no-padding height2">
                        <h2 class="menushka"><strong>Менюшка</strong></h2>
                        <ul class="unordered-list services-list">
							<?php foreach ($menuItems['main'] as $menuItem) {
								$page = $menuItem->page;
								?>
                                <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                    <li><?= $menuItem->title ? $menuItem->title : $page->title ?></li>
                                </a>
							<?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12 col-sm-6">
                            <div class="element  clearfix col1-3 b-b">
								<?php
								$next = next($menuItems['animateSquare']);
								$menuItem = $next ? $next : reset($menuItems['animateSquare']);
								if ($menuItem) {
									$page = $menuItem->page;
									$title = $menuItem->title ? $menuItem->title : $page->title;
									$next = next($bottomMenu);
									$folder = $next ? $next : reset($bottomMenu);
									?>
                                    <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                        <figure class="images">
											<?= Html::img(\Yii::getAlias('@filesView') . $folder, [
												'alt'   => $title,
												'title' => $title,
												'class' => 'slip'
											]) ?>
                                        </figure>
                                    </a>
								<?php } ?>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6">
                            <div class="element  clearfix col1-3">
								<?php
								$next = next($menuItems['animateSquare']);
								$menuItem = $next ? $next : reset($menuItems['animateSquare']);
								if ($menuItem) {
									$page = $menuItem->page;
									$title = $menuItem->title ? $menuItem->title : $page->title;
									$next = next($bottomMenu);
									$folder = $next ? $next : reset($bottomMenu);
									?>
                                    <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                        <figure class="images">
											<?= Html::img(\Yii::getAlias('@filesView') . $folder, [
												'alt'   => $title,
												'title' => $title,
												'class' => 'slip'
											]) ?>
                                        </figure>
                                    </a>
								<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end .container -->
        </div>
    </section>

    <section id="bottom-menu-mobile" class="main-menu-content">
        <div class="row c-black">
            <div class="col-md-12">
                <div class="bottom-menu-mobile">
                    <h2><strong>Менюшка</strong></h2>
                    <ul>
						<?php foreach ($menuItems['main'] as $menuItem) {
							$page = $menuItem->page;
							?>
                            <a href="<?= $menuItem->link ? $menuItem->link : '/' . $page->url ?>">
                                <li><?= $menuItem->title ? $menuItem->title : $page->title ?></li>
                            </a>
						<?php } ?>
                        <a href="/photogallery">
                            <li>Фотогалерея</li>
                        </a>
                        <a href="/sitemap">
                            <li>Карта сайта</li>
                        </a>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 main_news">
                <a href="<?= $news->page->url ?>">
					<?= Html::img(Yii::getAlias('@filesView') . $news->previewImage, [
						'alt'   => $news->title,
						'title' => $news->title
					]) ?>
                    <div class="my-button">
                        <div class="my-button-top">Главная новость</div>
                    </div>
                    <div class="my-button">
                        <div class="my-button-bottom">
							<?= $news->previewText ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- end .container -->
    </section>
</div>

<!-- =========================
    END MAIN CONTENT BLOCK
============================== -->

<!-- =========================
   RIGHT BLOCK
============================== -->
<aside id="right-feature">
    <div class="ov-h">
        <!-- === RIGHT ITEM === -->
        <div class="right-item right-item-1"
             style="background-image: url(<?= Yii::getAlias('@filesView') . reset($rightMenu) ?>)"
             data-stellar-background-ratio="0.3">
            <div class="right-item-overlay">
                <div>
                    <div class="right-item-title">
                        ФОТОГАЛЕРЕЯ
                    </div>

                    <div class="right-item-icons">
                        <?php foreach ($years as $year) { ?>
                            <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                               data-lightbox="feature" href="/photogallery/<?= $year->year ?>"><?= $year->year ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="ov-h">
        <!-- === RIGHT ITEM === -->
        <div class="right-item right-item-2"
             style="background-image: url(<?= Yii::getAlias('@filesView') . next($rightMenu) ?>)"
             data-stellar-background-ratio="0.3">
            <div class="right-item-overlay">
                <div>
                    <div class="right-item-title">
                        ВИДЕОГАЛЕРЕЯ
                    </div>
                    <div class="right-item-icons">
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="https://www.youtube.com/channel/UCylSYGIPB3OidOeQXQ38Rgw/playlists"><img
                                    src="img/icon/pol.png"></a>
                        <a class="animated wow fadeInDown inv" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="https://www.youtube.com/channel/UCylSYGIPB3OidOeQXQ38Rgw/playlists"><img src="img/icon/lec.png"></a>
                        <a class="animated wow fadeInDown inv" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="https://www.youtube.com/playlist?list=PLPRuX9QmtJacPekTI3n_AIiU9BS39mmyQ"><img src="img/icon/blog.png"></a>
                        <a class="animated wow fadeInDown inv" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="https://www.youtube.com/channel/UCylSYGIPB3OidOeQXQ38Rgw/playlists"><img
                                    src="img/icon/gm.png"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ov-h">
        <!-- === RIGHT ITEM === -->
        <div class="right-item right-item-3"
             style="background-image: url(<?= Yii::getAlias('@filesView') . next($rightMenu) ?>)"
             data-stellar-background-ratio="0.3">
            <div class="right-item-overlay">
                <div>
                    <div class="right-item-title">
                        РЕЗУЛЬТАТЫ БАЗОВЫХ ФИГУР
                    </div>
                    <div class="right-item-icons">
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="/championship">Eraser</a>
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="/championship">Shiso GP</a>
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="/championship">Pita GP</a>
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="/championship">GP8</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="top-list text-center">
        ГАЗУЙ
        <img src="img/top.png">
    </div>


</aside>
<!-- =========================
   END RIGHT BLOCK
============================== -->