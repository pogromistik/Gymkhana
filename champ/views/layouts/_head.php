<?php
use yii\helpers\Url;

?>

<!-- ШАПКА САЙТА -->
<div class="header">
    <div class="white-menu">
        <!-- меню -->
        <div class="container-fluid">
            <nav role="navigation" class="navbar" id="nav">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php if (!\Yii::$app->user->isGuest) { ?>
                    <div class="navbar-mobile-items">
                        <a href="#" class="notices"><span class="fa fa-bell green"></span>
                            <span id="newNoticesMobile"></span></a>
                        <div class="modal-notices">
                            <div class="text-right closeNotices">x</div>
                            <div class="text">
                            </div>
                            <div class="show-all text-center pt-10">
				                <?= \yii\bootstrap\Html::a(\Yii::t('app', 'Показать все уведомления'), ['/notices/all']) ?>
                            </div>
                        </div>
                    </div>
                    <ul class="navbar-left navbar-mobile-items lk">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle"
                               href="#"><span class="fa fa-user"></span></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="/profile/index"><?= \Yii::t('app', 'Профиль') ?></a></li>
                                <li><a href="/profile/stats"><?= \Yii::t('app', 'Статистика') ?></a></li>
                                <li><a href="/profile/info"><?= \Yii::t('app', 'Заявки на участие') ?></a></li>
                                <li><a href="/figures/send-result"><?= \Yii::t('app', 'Отправить результат') ?></a></li>
                                <li><a href="/profile/change-class"><?= \Yii::t('app', 'Смена класса') ?></a></li>
                                <li><a href="/site/logout"><?= \Yii::t('app', 'Выход') ?></a></li>
                            </ul>
                        </li>
                    </ul>
                    <?php } else { ?>
                        <div class="navbar-mobile-items">
                            <a href="/site/login" class="notices"><span class="fa fa-user"></span>
                                <span id="newNotices"></span></a>
                        </div>
                    <?php } ?>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="/"><?= \Yii::t('app', 'Главная') ?></a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['site/documents']) ?>">Документы</a>
                        </li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">Соревнования <b
                                        class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="/competitions/schedule">Расписание</a></li>
                                <li class="dropdown dropdown-submenu"><a href="#" data-toggle="dropdown">Результаты</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?= Url::to(['/competitions/results', 'by' => \champ\controllers\CompetitionsController::RESULTS_FIGURES]) ?>">Базовые фигуры</a> </li>
                                        <li><a href="<?= Url::to(['/competitions/results', 'by' => \champ\controllers\CompetitionsController::RESULTS_RUSSIA]) ?>">Чемпионаты России</a></li>
                                        <li><a href="<?= Url::to(['/competitions/results', 'by' => \champ\controllers\CompetitionsController::RESULTS_REGIONAL]) ?>">Региональные соревнования</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="/athletes/list">Спортсмены</a>
                        </li>
                        <li>
                            <a href="http://gymkhana74.ru/russia" target="_blank">Россия</a>
                        </li>
                        <li><?php if (Yii::$app->user->isGuest) { ?>
                                <a href="/site/login">Вход</a>
							<?php } else { ?>
                        <li class="dropdown pk-menu-items">
                            <a data-toggle="dropdown" class="dropdown-toggle"
                               href="#"><?= \Yii::$app->user->identity->getFullName() ?> <b
                                        class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="/profile/index">Профиль</a></li>
                                <li><a href="/profile/stats">Статистика</a></li>
                                <li><a href="/profile/info">Заявки на участие</a></li>
                                <li><a href="/figures/send-result">Отправить результат</a></li>
                                <li><a href="/profile/change-class">Смена класса</a></li>
                                <li><a href="/site/logout">Выход</a></li>
                            </ul>
                        </li>
                        <li class="pk-menu-items">
                            <a href="#" class="notices"><span class="fa fa-bell green"></span>
                            <span id="newNotices"></span></a>
                            <div class="modal-notices">
                                <div class="text-right closeNotices">x</div>
                                <div class="text">
                                </div>
                                <div class="show-all text-center pt-10">
                                    <?= \yii\bootstrap\Html::a('Показать все уведомления', ['/notices/all']) ?>
                                </div>
                            </div>
                        </li>
						<?php } ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div><!-- КОНЕЦ: ШАПКА САЙТА -->