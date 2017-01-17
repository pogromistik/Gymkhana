<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use site\assets\PagesAsset;
use common\models\GroupMenu;
use common\models\MenuItem;

PagesAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $this->context->description ?>">
    <meta name="keywords" content="<?= $this->context->keywords ?>">
	<?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->context->pageTitle) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- ШАПКА САЙТА -->
<div class="header">
    <div class="white-menu">
        <!-- меню -->
        <div class="">
            <nav role="navigation" class="navbar">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="top_nav logo">
                        <a href="/">

                            <div class="logo1">
								<?= \yii\bootstrap\Html::img('/img/logo_green.png', [
									'alt'   => 'Мотоджимхана Челябинск',
									'title' => 'Мотоджимхана Челябинск'
								]) ?>
                            </div>
                            <div class="logo2">
								<?= \yii\bootstrap\Html::img('/img/logo.png', [
									'alt'   => 'Мотоджимхана Челябинск',
									'title' => 'Мотоджимхана Челябинск'
								]) ?>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
						<?php
						/** @var GroupMenu $group */
						foreach (GroupMenu::find()->orderBy(['sort' => SORT_ASC])->all() as $group) { ?>
                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?= $group->title ?> <b
                                            class="caret"></b></a>
                                <ul role="menu" class="dropdown-menu">
									<?php
									/** @var MenuItem $item */
									foreach ($group->items as $item) { ?>
                                        <li><a href="<?= $item->link ? $item->link : '/'.$item->page->url ?>">
												<?= $item->title ? $item->title : $item->page->title ?></a>
                                        </li>
									<?php } ?>
                                </ul>
                            </li>
						<?php } ?>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div><!-- КОНЕЦ: ШАПКА САЙТА -->

<?= $content ?>

<!-- ПОДВАЛ -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <!-- дублирование меню -->
            <div class="col-md-8 col-sm-6 col-xs-12">
                <h4>Навигация по сайту</h4>
                <ul>
                    <li><a href="/">О проекте</a></li>
                    <li><a href="/">Правила</a></li>
                    <li><a href="/">Маршалы</a></li>
                    <li><a href="/">Помочь проекту</a></li>
                    <li><a href="/">Адреса</a></li>
                    <li><a href="/">Спонсоры</a></li>
                    <li><a href="/">Предстоящие этапы</a></li>
                    <li><a href="/">Результаты соревнований</a></li>
                    <li><a href="/">Результаты базовых фигур</a></li>
                    <li><a href="/">Скачать фигуры</a></li>
                    <li><a href="/">Фотогалерея</a></li>
                    <li><a href="/">Видеогалерея</a></li>
                    <li><a href="/">Россия</a></li>
                    <li><a href="/">Урал</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <h4>Контакты</h4>
                <?php
                /** @var \common\models\Contacts $contacts */
                $contacts = \common\models\Contacts::find()->one(); ?>
                <?= $contacts->phone ?><br>
                <?= $contacts->email ?><br>
                <?= $contacts->smallInfo ?>
            </div>
        </div>
        <!-- сылка на разработчика -->
        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12 developer">
                <a href="https://vk.com/id19792817" target="_blank">Сайт разработан чудесным человеком</a>
            </div>
        </div>
    </div>
</footer><!-- КОНЕЦ: ПОДВАЛ -->


<div class="preloader" id="preloader"></div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
