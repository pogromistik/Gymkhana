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
                            <span id="newNotices"></span></a>
                        <div class="modal-notices">
                            <div class="text-right" id="closeNotices">x</div>
                            <div class="text">
                            </div>
                            <div class="show-all text-center pt-10">
				                <?= \yii\bootstrap\Html::a('Показать все уведомления', ['/notices/all']) ?>
                            </div>
                        </div>
                    </div>
                    <ul class="navbar-left navbar-mobile-items lk">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle"
                               href="#"><span class="fa fa-user"></span></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="/profile/index">Профиль</a></li>
                                <li><a href="/profile/compare-with">Статистика</a></li>
                                <li><a href="/site/logout">Выход</a></li>
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
                            <a href="/">Главная</a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['site/documents']) ?>">Документы</a>
                        </li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">Соревнования <b
                                        class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="/competitions/schedule">Расписание</a></li>
                                <li><a href="/competitions/results">Результаты</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="/athletes/list">Спортсмены</a>
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
                                <li><a href="/profile/compare-with">Статистика</a></li>
                                <li><a href="/site/logout">Выход</a></li>
                            </ul>
                        </li>
                        <li class="pk-menu-items">
                            <a href="#" class="notices"><span class="fa fa-bell green"></span>
                            <span id="newNotices"></span></a>
                            <div class="modal-notices">
                                <div class="text-right" id="closeNotices">x</div>
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