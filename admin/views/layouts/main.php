<?php

/* @var $this \yii\web\View */
/* @var $content string */

use admin\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- /.navbar-toggle -->
            <a class="navbar-brand" href="/"><?= \Yii::$app->name ?></a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="<?= Url::to(['/site/logout']) ?>" data-method='post'><i
                                    class="fa fa-sign-out fa-fw"></i> <?= Yii::t('app', 'Выход') ?></a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="<?= Url::to(['/main/index']) ?>"><i
                                    class="fa fa-table fa-fw"></i> Главная страница</a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['/news/index']) ?>"><i
                                    class="fa fa-table fa-fw"></i> Новости</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-shopping-cart fa-fw"></i> О проекте<span
                                    class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?= Url::to(['/about/index']) ?>"> О проекте</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/about/regular']) ?>"> Правила</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/marshals/index']) ?>"> Маршалы</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/about/contacts']) ?>"> Контакты (помочь проекту + адреса)</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/about/sponsors']) ?>"> Спонсоры</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-shopping-cart fa-fw"></i> Галерея<span
                                    class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?= Url::to(['/video/index']) ?>"> Видеогалерея</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/album/index']) ?>"> Фотогалерея</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?= Url::to(['/russia/index']) ?>"><i
                                    class="fa fa-table fa-fw"></i> Россия</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-shopping-cart fa-fw"></i> Дополнительно<span
                                    class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?= Url::to(['/dop-pages/index']) ?>"> Дополнительные страницы</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/additional/years']) ?>"> Года</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['qq']) ?>"> Картинки для предзагрузки</a>
                            </li>
							<?php if (\Yii::$app->user->can('developer')) { ?>
                                <li>
                                    <a href="<?= Url::to(['/additional/layouts']) ?>"> Шаблоны</a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/pages/index']) ?>"> Страницы</a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/user/signup']) ?>"> Пользователи</a>
                                </li>
							<?php } ?>
                            <li>
                                <a href="<?= Url::to(['/menu/index']) ?>"> Меню</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?= $this->title ?></h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="breadcrumbs">
			<?= Breadcrumbs::widget([
				'homeLink' => false,
				'links'    => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
        </div>
		<?= $content ?>
    </div>
    <!-- /#page-wrapper -->

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
