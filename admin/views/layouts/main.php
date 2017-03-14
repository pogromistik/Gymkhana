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
use \admin\assets\BootboxAsset;

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
            <li>
		        <?php $countNewFigure = \common\models\TmpFigureResult::find()
			        ->where(['isNew' => 1])->count() ?>
                <a href="<?= Url::to(['/competitions/tmp-figures/index']) ?>"><i
                            class="fa fa-bullhorn fa-fw"></i> Новые результаты фигур <?= $countNewFigure ? '(' . $countNewFigure . ')' : '' ?></a>
            </li>
            <li>
	            <?php $countNewReg = \common\models\TmpParticipant::find()
                    ->where(['status' => \common\models\TmpParticipant::STATUS_NEW])->count() ?>
                <a href="<?= Url::to(['/competitions/tmp-participant/index']) ?>"><i
                            class="fa fa-registered fa-fw"></i> Новые регистрации <?= $countNewReg ? '(' . $countNewReg . ')' : '' ?></a>
            </li>
            <li>
				<?php $count = \common\models\Feedback::find()->where(['isNew' => 1])->count() ?>
                <a href="<?= Url::to(['/competitions/feedback/index']) ?>"><i
                            class="fa fa-bell fa-fw"></i> Обратная связь <?= $count ? '(' . $count . ')' : '' ?></a>
                
            </li>
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
					<?php if (\Yii::$app->user->can('admin')) { ?>
                        <li>
                            <a href="<?= Url::to(['/main/index']) ?>"><i
                                        class="fa fa-table fa-fw"></i> Главная страница</a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/news/index']) ?>"><i
                                        class="fa fa-newspaper-o fa-fw"></i> Новости</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-info-circle fa-fw"></i> О проекте<span
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
                                    <a href="<?= Url::to(['/about/sponsors']) ?>"> Спонсоры</a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/about/help-project']) ?>"> Помочь проекту</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-picture-o fa-fw"></i> Галерея<span
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
                            <a href="#"><i class="fa fa-rocket fa-fw"></i> Фигуры и трассы<span
                                        class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?= Url::to(['/tracks/index']) ?>"> Скачать трассы</a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/figures/index']) ?>"> Результаты базовых фигур</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/russia/index']) ?>"><i
                                        class="fa fa fa-globe fa-fw"></i> Россия</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-cog fa-fw"></i> Дополнительно<span
                                        class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?= Url::to(['/additional/links']) ?>"> Ссылки на соц сети</a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/about/contacts']) ?>"> Контактная информация</a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/additional/years']) ?>"> Года</a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/additional/preloader']) ?>"> Картинки для предзагрузки</a>
                                </li>
								<?php if (\Yii::$app->user->can('developer')) { ?>
                                    <li>
                                        <a href="<?= Url::to(['/additional/layouts']) ?>"> Шаблоны</a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::to(['/pages/index']) ?>"> Страницы</a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::to(['/user/admin']) ?>"> Пользователи</a>
                                    </li>
								<?php } ?>
                                <li>
                                    <a href="<?= Url::to(['/menu/index']) ?>"> Меню</a>
                                </li>
                            </ul>
                        </li>
					<?php } ?>
					<?php if (\Yii::$app->user->can('competitions')) { ?>
                        <li class="competitions active">
                            <a href="#"><i class="fa fa-motorcycle fa-fw"></i> СОРЕВНОВАНИЯ<span
                                        class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?= Url::to(['/competitions/help/years']) ?>"> Года</a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/competitions/additional/points']) ?>"> Баллы для чемпионатов</a>
                                </li>
                                <li>
                                    <a data-addr="/competitions/news"
                                            href="<?= Url::to(['/competitions/news/index']) ?>"> Новости</a>
                                </li>
                                <li>
                                    <a data-addr = "/competitions/documents"
                                            href="<?= Url::to(['/competitions/documents/index']) ?>"> Документы</a>
                                </li>
                                <li>
                                    <a data-addr="/competitions/classes"
                                            href="<?= Url::to(['/competitions/classes/index']) ?>"> Классы спортсменов</a>
                                </li>
                                <li>
                                    <a data-addr="/competitions/athlete"
                                            href="<?= Url::to(['/competitions/athlete/index']) ?>"> Спортсмены</a>
                                </li>
                                <li class="level-2 active">
                                    <a href="<?= Url::to(['/competitions/championships/index']) ?>"> Чемпионаты<span
                                                class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
	                                    <?php foreach (\common\models\Championship::$groupsTitle as $groupId => $title) { ?>
                                            <li><?= Html::a($title, ['/competitions/championships/index', 'groupId' => $groupId]) ?></li>
	                                    <?php } ?>
                                    </ul>
                                </li>
                                <li>
                                    <a data-addr="/competitions/figures" href="<?= Url::to(['/competitions/figures/index']) ?>"> Фигуры</a>
                                </li>
                                <li>
                                    <a data-addr="/competitions/notice" href="<?= Url::to(['/competitions/notice/index']) ?>"> Отправить уведомление</a>
                                </li>
                            </ul>
                        </li>
					<?php } ?>
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
