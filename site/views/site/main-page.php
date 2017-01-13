<?php
use yii\bootstrap\Html;

/**
 * @var \common\models\MainPhoto[] $slider
 * @var \common\models\MainPhoto[] $leftMenu
 * @var \common\models\MainPhoto[] $bottomMenu
 * @var \common\models\Link[]      $social
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
            <nav id="main-nav">
                <div id="menu-close-button">&times;</div>

                <ul id="options" class="option-set clearfix" data-option-key="filter">
                    <li><a href="project/about/">Кто?</a></li>
                    <li><a href="russia/russia/">Где?</a></li>
                    <li><a href="project/address.html">Когда?</a></li>
                </ul>
            </nav>
        </div>
    </section>
    <!-- =========================
		END OUR SERVICES
	============================== -->

    <section id="bottom-menu-pk" class="main-menu-content">
        <div class="menu-bottom">
            <div class="row c-black">
                <div class="col-md-6 main_news">
                    <a href="news/archive/pervyij-etap-chempionata-urala-po-moto-dzhimxane-na-vyistavke-auto-show.html">
                        <img src="img/news/6/small.jpg"
                             alt="ПЕРВЫЙ ЭТАП ЧЕМПИОНАТА УРАЛА ПО МОТО ДЖИМХАНЕ НА ВЫСТАВКЕ «AUTO SHOW»"
                             title="ПЕРВЫЙ ЭТАП ЧЕМПИОНАТА УРАЛА ПО МОТО ДЖИМХАНЕ НА ВЫСТАВКЕ «AUTO SHOW»"/>
                        <div class="my-button">
                            <div class="my-button-top">Главная новость</div>
                        </div>
                        <div class="my-button">
                            <div class="my-button-bottom">28 мая 2016 года, в субботу, в Челябинске пройдет уникальное
                                событие – первый этап Чемпионата Уральского региона по мото джимхане!
                            </div>
                        </div>

                    </a>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-6 height1">
                            <div class="element  col1-3 home photography b-b">
                                <a href="project/help.html" title="">
                                    <figure class="images">
                                        <img src="img/menu/tPCWJiz45OA.jpg" alt="Помочь проекту" class="slip"/>
                                    </figure>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="element  col1-3 home webdesign b-b b-l">
                                <a href="project/marshal/" title="">
                                    <figure class="images">
                                        <img src="img/menu/B9k1SExMXCA.jpg" alt="Маршалы" class="slip"/>
                                    </figure>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="element  clearfix col1-3 home grey height0">
                                <div class="icons team"></div>
                                <h3>Стандартные фигуры</h3>
                                <a href="competition/figure/" data-title="Who?" class="splink">
                                    <div class="bottom">
                                        <p>Поехали!</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="element  clearfix col1-3 home portfolio illustration b-l">
                                <a href="project/regular.html" title="">
                                    <figure class="images">
                                        <img src="img/menu/h7qUv85F28s.jpg" alt="Правила" class="slip"/>
                                    </figure>
                                </a>
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
                                <div class="element  clearfix col1-3 home grey height0">
                                    <div class="icons illustration"></div>
                                    <h3>Трассы соревнований</h3>
                                    <a href="competition/figure/" data-title="What?" class="splink">
                                        <div class="bottom">
                                            <p>Поехали!</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="element  clearfix col1-3 portfolio photography home"><a
                                            href="project/sponsors.html" title="">
                                        <figure class="images">
                                            <img src="img/menu/DSC_9221.jpg" alt="Спонсоры" class="slip"/>
                                        </figure>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="element  clearfix col1-3 portfolio photography home">
                                    <a href="news/archive/" title="">
                                        <figure class="images">
                                            <img src="img/menu/l8Z8IeXD19o.jpg" alt="Архив новостей" class="slip"/>
                                        </figure>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="element  clearfix col1-3 home grey height0">
                                    <div class="icons network"></div>
                                    <h3>Трассы тренировок</h3>
                                    <a href="competition/figure/" data-title="Where?" class="splink">
                                        <div class="bottom">
                                            <p>Поехали!</p>
                                        </div>
                                    </a>
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
                            <div class="element  clearfix col1-3 portfolio photography home b-b">
                                <a href="competition/result/" title="">
                                    <figure class="images">
                                        <img src="img/menu/BzPZ2VUgbz0.jpg" alt="Результаты соревнований" class="slip"/>
                                    </figure>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6">
                            <div class="element  clearfix col1-3 home portfolio illustration">

                                <figure class="images">
                                    <img src="img/menu/DSC_9196.jpg" alt="<img src='img/menu/DSC_9196.jpg'/>"
                                         class="slip"/>
                                </figure>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="element  clearfix col2-3 home grey no-padding height2">
                        <h2 class="menushka"><strong>Менюшка</strong></h2>
                        <ul class="unordered-list services-list">
                            <a href="project/about/">
                                <li>О проекте</li>
                            </a>
                            <a href="news/archive/">
                                <li>Новости</li>
                            </a>
                            <a href="competition/predstoyashhie-etapyi/">
                                <li>Соревнования</li>
                            </a>
                            <a href="gallery/photogallery/">
                                <li>Галерея</li>
                            </a>
                            <a href="russia/russia/">
                                <li>Россия</li>
                            </a>

                            <!--<a href="#"><li>Social Media <span class="arrow">→</span></li></a>-->
                        </ul>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12 col-sm-6">
                            <div class="element  clearfix col1-3 home portfolio illustration b-b">
                                <a href="russia/ural.html" title="">
                                    <figure class="images">
                                        <img src="img/menu/C_nGuOzBTFQ.jpg" alt="Урал" class="slip"/>
                                    </figure>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6">
                            <div class="element  clearfix col1-3 home portfolio illustration">

                                <figure class="images">
                                    <img src="img/menu/3hsmlkRxGSA.jpg" alt="<img src='img/menu/3hsmlkRxGSA.jpg'/>"
                                         class="slip"/>
                                </figure>

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
                        <a href="project/about/">
                            <li>О проекте</li>
                        </a>
                        <a href="news/archive/">
                            <li>Новости</li>
                        </a>
                        <a href="competition/predstoyashhie-etapyi/">
                            <li>Соревнования</li>
                        </a>
                        <a href="gallery/photogallery/">
                            <li>Галерея</li>
                        </a>
                        <a href="russia/russia/">
                            <li>Россия</li>
                        </a>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 main_news">
                <a href="news/archive/pervyij-etap-chempionata-urala-po-moto-dzhimxane-na-vyistavke-auto-show.html">
                    <img src="img/news/6/small.jpg"
                         alt="ПЕРВЫЙ ЭТАП ЧЕМПИОНАТА УРАЛА ПО МОТО ДЖИМХАНЕ НА ВЫСТАВКЕ «AUTO SHOW»"
                         title="ПЕРВЫЙ ЭТАП ЧЕМПИОНАТА УРАЛА ПО МОТО ДЖИМХАНЕ НА ВЫСТАВКЕ «AUTO SHOW»"/>
                    <div class="my-button">
                        <div class="my-button-top">Главная новость</div>
                    </div>
                    <div class="my-button">
                        <div class="my-button-bottom">28 мая 2016 года, в субботу, в Челябинске пройдет уникальное
                            событие – первый этап Чемпионата Уральского региона по мото джимхане!
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
        <div class="right-item right-item-1" data-stellar-background-ratio="0.3">
            <div class="right-item-overlay">
                <div>
                    <div class="right-item-title">
                        ФОТОГАЛЕРЕЯ
                    </div>


                    <div class="right-item-icons">

                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="index.php?id=12">2016</a>
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="index.php?id=11">2015</a>
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="index.php?id=10">2014</a>


                    </div>


                </div>
            </div>
        </div>
    </div>


    <div class="ov-h">
        <!-- === RIGHT ITEM === -->
        <div class="right-item right-item-2" data-stellar-background-ratio="0.3">
            <div class="right-item-overlay">
                <div>
                    <div class="right-item-title">
                        ВИДЕОГАЛЕРЕЯ
                    </div>
                    <div class="right-item-icons">
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="gallery/videogallery/polevskoj/"><img
                                    src="img/icon/pol.png"></a>
                        <a class="animated wow fadeInDown inv" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="gallery/videogallery/lekczii/"><img src="img/icon/lec.png"></a>
                        <a class="animated wow fadeInDown inv" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="gallery/videogallery/blog/"><img src="img/icon/blog.png"></a>
                        <a class="animated wow fadeInDown inv" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="gallery/videogallery/dzhimxana/"><img
                                    src="img/icon/gm.png"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ov-h">
        <!-- === RIGHT ITEM === -->
        <div class="right-item right-item-3" data-stellar-background-ratio="0.3">
            <div class="right-item-overlay">
                <div>
                    <div class="right-item-title">
                        РЕЗУЛЬТАТЫ БАЗОВЫХ ФИГУР
                    </div>
                    <div class="right-item-icons">
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="index.php?id=103">Eraser</a>
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="index.php?id=102">Shiso GP</a>
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="index.php?id=45">Pita GP</a>
                        <a class="animated wow fadeInDown" data-wow-duration=".3s" data-wow-delay=".0s"
                           data-lightbox="feature" href="index.php?id=14">GP8</a>
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