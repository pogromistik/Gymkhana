<?php
use yii\helpers\Url;
use yii\helpers\Html;

?>

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
		<?php if (\Yii::$app->user->can('refereeOfCompetitions')) { ?>
			<?php $countNewLK = \common\models\TmpAthlete::find()
				->where(['status' => \common\models\TmpAthlete::STATUS_NEW])->count() ?>
			<?php if ($countNewLK) { ?>
                <li>
                    <a href="<?= Url::to(['/competitions/tmp-athletes/index']) ?>"><i
                                class="fa fa-registered fa-fw"></i> Заявки на
                        ЛК <?= $countNewLK ? '(' . $countNewLK . ')' : '' ?></a>
                </li>
			<?php } ?>
			
			<?php if (\Yii::$app->user->can('canChangeClass')) { ?>
				<?php $countNewClassesRequest = \common\models\ClassesRequest::find()
					->where(['status' => \common\models\ClassesRequest::STATUS_NEW])->count() ?>
				<?php if ($countNewClassesRequest) { ?>
                    <li>
                        <a href="<?= Url::to(['/competitions/classes-request/index']) ?>"><i
                                    class="fa fa-hand-spock-o fa-fw"></i> Запросы на смену
                            класса <?= $countNewClassesRequest ? '(' . $countNewClassesRequest . ')' : '' ?></a>
                    </li>
				<?php } ?>
			<?php } ?>
			
			<?php if (\Yii::$app->user->can('canApproveFigureResults')) { ?>
				<?php $countNewFigure = \common\models\TmpFigureResult::find()
					->where(['isNew' => 1])->count() ?>
				<?php if ($countNewFigure) { ?>
                    <li>
                        <a href="<?= Url::to(['/competitions/tmp-figures/index']) ?>"><i
                                    class="fa fa-bullhorn fa-fw"></i> Новые результаты
                            фигур <?= $countNewFigure ? '(' . $countNewFigure . ')' : '' ?></a>
                    </li>
				<?php } ?>
			<?php } ?>
			
			<?php $countNewReg = \common\models\TmpParticipant::countNewReg();
			?>
			<?php if ($countNewReg) { ?>
                <li>
                    <a href="<?= Url::to(['/competitions/tmp-participant/index']) ?>"><i
                                class="fa fa-registered fa-fw"></i> Регистрации на
                        этап <?= $countNewReg ? '(' . $countNewReg . ')' : '' ?></a>
                </li>
			<?php } ?>
			
			<?php if (\Yii::$app->user->can('changeSpecialChamps')) { ?>
				<?php $countNewSpecialReg = \common\models\RequestForSpecialStage::countNewReg();
				?>
				<?php if ($countNewSpecialReg) { ?>
                    <li>
                        <a href="<?= Url::to(['/competitions/special-champ/registrations']) ?>"><i
                                    class="fa fa-registered fa-fw"></i> Спец. этап
                            <?= $countNewSpecialReg ? '(' . $countNewSpecialReg . ')' : '' ?></a>
                    </li>
				<?php } ?>
			<?php } ?>
			
			<?php if (\Yii::$app->user->can('projectOrganizer')) { ?>
                <li>
					<?php $count = \common\models\Feedback::find()->where(['isNew' => 1])->count() ?>
                    <a href="<?= Url::to(['/competitions/feedback/index']) ?>"><i
                                class="fa fa-bell fa-fw"></i> Обратная связь <?= $count ? '(' . $count . ')' : '' ?>
                    </a>

                </li>
			<?php } ?>
		<?php } ?>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="<?= Url::to(['/site/logout']) ?>" data-method='post'><i
                                class="fa fa-sign-out fa-fw"></i> <?= Yii::t('app', 'Выход') ?></a>
                </li>
                <li><a href="<?= Url::to(['/profile/index']) ?>"><i
                                class="fa fa-user fa-fw"></i> <?= Yii::t('app', 'Профиль') ?></a>
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
				<?php if (\Yii::$app->user->can('developer')) { ?>
                    <li>
                        <a href="#"><i class="fa fa-cogs fa-fw"></i> РАЗРАБОТЧИКУ<span
                                    class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?= Url::to(['/competitions/developer/repeat-athletes']) ?>"> Повторы
                                    спортсменов</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/competitions/developer/repeat-figures-time']) ?>"> Повторы
                                    времени фигур</a>
                            </li>
                        </ul>
                    </li>
				<?php } ?>
				<?= $this->render('_nav-competitions') ?>
				<?php
				if (\Yii::$app->user->can('developer')) { ?>
                    <li class="translate-bg">
                        <a href="#">
                            <i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>&nbsp;ПЕРЕВОД<span
                                    class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?= Url::to(['/competitions/translate-messages/index']) ?>">Слова для
                                    перевода</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/competitions/translate-messages/translate']) ?>">Переводы</a>
                            </li>
                        </ul>
                    </li>
				<?php } elseif (\Yii::$app->user->can('translate')) {
					?>
                    <li class="translate-bg">
                        <a href="<?= Url::to(['/competitions/translate-messages/translate']) ?>"><i
                                    class="fa fa-sort-alpha-asc" aria-hidden="true"></i>
                            &nbsp;ПЕРЕВОД</a>
                    </li>
					<?php
				} ?>
				<?php if (\Yii::$app->user->can('developer')) {
					?>
                    <li>
                        <a href="<?= Url::to(['/developer/work-page']) ?>"><i
                                    class="fa fa-lock" aria-hidden="true"></i>
                            &nbsp;Заблокировать</a>
                    </li>
					<?php
				} ?>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>