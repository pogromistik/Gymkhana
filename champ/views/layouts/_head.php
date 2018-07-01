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
									<?= \yii\bootstrap\Html::a(\Yii::t('app', \Yii::t('app', 'Показать все уведомления')), ['/notices/all']) ?>
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
                                    <li><a href="/figures/send-result"><?= \Yii::t('app', 'Отправить результат') ?></a>
                                    </li>
                                    <li><a href="/profile/change-class"><?= \Yii::t('app', 'Смена класса') ?></a></li>
                                    <li><a href="/site/logout"><?= \Yii::t('app', 'Выход') ?></a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="navbar-mobile-items">
                            <div class="mobile-language flags">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<?= \yii\helpers\Html::img(\common\models\TranslateMessage::$smallLanguagesImg[\Yii::$app->language]) ?>
                                    <b
                                            class="caret"></b></a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="#" class="change-language">
											<?= \yii\helpers\Html::img(
												\common\models\TranslateMessage::$smallLanguagesImg[\common\models\TranslateMessage::LANGUAGE_RU]
											) ?>
											<?= \common\models\TranslateMessage::$smallLanguagesTitle[\common\models\TranslateMessage::LANGUAGE_RU] ?>
                                        </a></li>
                                    <li><a href="#" class="change-language">
											<?= \yii\helpers\Html::img(
												\common\models\TranslateMessage::$smallLanguagesImg[\common\models\TranslateMessage::LANGUAGE_EN]
											) ?>
											<?= \common\models\TranslateMessage::$smallLanguagesTitle[\common\models\TranslateMessage::LANGUAGE_EN] ?>
                                        </a></li>
                                </ul>
                            </div>
                        </div>
					<?php } else { ?>
                        <div class="navbar-mobile-items">
                            <a href="/site/login" class="notices"><span class="fa fa-user"></span>
                                <span id="newNotices"></span></a>
                            <div class="mobile-language flags">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<?= \yii\helpers\Html::img(\common\models\TranslateMessage::$smallLanguagesImg[\Yii::$app->language]) ?>
                                    <b
                                            class="caret"></b></a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="//gymkhana-cup.ru<?= \Yii::$app->request->url ?>">
											<?= \yii\helpers\Html::img(
												\common\models\TranslateMessage::$smallLanguagesImg[\common\models\TranslateMessage::LANGUAGE_RU]
											) ?>
											<?= \common\models\TranslateMessage::$smallLanguagesTitle[\common\models\TranslateMessage::LANGUAGE_RU] ?>
                                        </a></li>
                                    <li><a href="//gymkhana-cup.com<?= \Yii::$app->request->url ?>">
											<?= \yii\helpers\Html::img(
												\common\models\TranslateMessage::$smallLanguagesImg[\common\models\TranslateMessage::LANGUAGE_EN]
											) ?>
											<?= \common\models\TranslateMessage::$smallLanguagesTitle[\common\models\TranslateMessage::LANGUAGE_EN] ?>
                                        </a></li>
                                </ul>
                            </div>
                        </div>
					<?php } ?>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="/"><?= \Yii::t('app', 'Главная') ?></a>
                        </li>
                        <li class="dropdown pk-menu-items">
                            <a data-toggle="dropdown" class="dropdown-toggle"
                               href="#"><?= \Yii::t('app', 'Информация') ?> <b
                                        class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="<?= Url::to(['site/documents']) ?>"><?= \Yii::t('app', 'Документы') ?></a>
                                </li>
                                <li><a href="<?= Url::to(['site/tracks']) ?>"><?= \Yii::t('app', 'Трассы') ?></a></li>
                                <li>
                                    <a href="<?= Url::to(['site/calculate']) ?>"><?= \Yii::t('app', 'Калькулятор') ?></a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/interviews/index']) ?>"><?= \Yii::t('app', 'Опросы') ?></a>
                                </li>
                            </ul>
                        </li>
                        <li class="mobile-menu-items"><a
                                    href="<?= Url::to(['site/documents']) ?>"><?= \Yii::t('app', 'Документы') ?></a>
                        </li>
                        <li class="mobile-menu-items"><a
                                    href="<?= Url::to(['site/tracks']) ?>"><?= \Yii::t('app', 'Трассы') ?></a></li>
                        <li class="mobile-menu-items"><a
                                    href="<?= Url::to(['site/calculate']) ?>"><?= \Yii::t('app', 'Калькулятор') ?></a>
                        </li>
                        <li class="mobile-menu-items">
                            <a href="<?= Url::to(['/interviews/index']) ?>"><?= \Yii::t('app', 'Опросы') ?></a>
                        </li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle"
                               href="#"><?= \Yii::t('app', 'Соревнования') ?> <b
                                        class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="/competitions/schedule"><?= \Yii::t('app', 'Календарь') ?></a></li>
                                <li>
                                    <a href="<?= Url::to(['/competitions/results', 'by' => \champ\controllers\CompetitionsController::RESULTS_FIGURES]) ?>">
										<?= \Yii::t('app', 'Базовые фигуры') ?>
                                    </a></li>
                                <li>
                                    <a href="<?= Url::to(['/competitions/results', 'by' => \champ\controllers\CompetitionsController::RESULTS_RUSSIA]) ?>">
										<?= \Yii::t('app', 'Чемпионаты России и мира') ?>
                                    </a></li>
                                <li>
                                    <a href="<?= Url::to(['/competitions/results', 'by' => \champ\controllers\CompetitionsController::RESULTS_REGIONAL]) ?>">
										<?= \Yii::t('app', 'Региональные соревнования') ?>
                                    </a></li>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="/athletes/list"><?= \Yii::t('app', 'Спортсмены') ?></a>
                        </li>
                        <li>
                            <a href="http://gymkhana74.ru/russia" target="_blank"><?= \Yii::t('app', 'Россия') ?></a>
                        </li>
						<?php if (\Yii::$app->user->isGuest) { ?>
                            <li class="dropdown pk-menu-items flags">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<?= \yii\helpers\Html::img(
										\common\models\TranslateMessage::$smallLanguagesImg[\Yii::$app->language]
									) ?>
                                    <b
                                            class="caret"></b></a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="//gymkhana-cup.ru<?= \Yii::$app->request->url ?>">
											<?= \yii\helpers\Html::img(
												\common\models\TranslateMessage::$smallLanguagesImg[\common\models\TranslateMessage::LANGUAGE_RU]
											) ?>
											<?= \common\models\TranslateMessage::$smallLanguagesTitle[\common\models\TranslateMessage::LANGUAGE_RU] ?>
                                        </a></li>
                                    <li><a href="//gymkhana-cup.com<?= \Yii::$app->request->url ?>">
											<?= \yii\helpers\Html::img(
												\common\models\TranslateMessage::$smallLanguagesImg[\common\models\TranslateMessage::LANGUAGE_EN]
											) ?>
											<?= \common\models\TranslateMessage::$smallLanguagesTitle[\common\models\TranslateMessage::LANGUAGE_EN] ?>
                                        </a></li>
                                </ul>
                            </li>
						<?php } else {
							?>
                            <li class="dropdown pk-menu-items flags">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
									<?= \yii\helpers\Html::img(
										\common\models\TranslateMessage::$smallLanguagesImg[\Yii::$app->language]
									) ?>
                                    <b
                                            class="caret"></b></a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="#" class="change-language">
											<?= \yii\helpers\Html::img(
												\common\models\TranslateMessage::$smallLanguagesImg[\common\models\TranslateMessage::LANGUAGE_RU]
											) ?>
											<?= \common\models\TranslateMessage::$smallLanguagesTitle[\common\models\TranslateMessage::LANGUAGE_RU] ?>
                                        </a></li>
                                    <li><a href="#" class="change-language">
											<?= \yii\helpers\Html::img(
												\common\models\TranslateMessage::$smallLanguagesImg[\common\models\TranslateMessage::LANGUAGE_EN]
											) ?>
											<?= \common\models\TranslateMessage::$smallLanguagesTitle[\common\models\TranslateMessage::LANGUAGE_EN] ?>
                                        </a></li>
                                </ul>
                            </li>
							<?php
						} ?>
                        <li><?php if (Yii::$app->user->isGuest) { ?>
                                <a href="/site/login"><?= \Yii::t('app', 'Вход') ?></a>
							<?php } else { ?>
                        <li class="dropdown pk-menu-items">
                            <a data-toggle="dropdown" class="dropdown-toggle"
                               href="#"><span class="fa fa-user green"></span> <b
                                        class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="/profile/index"><?= \Yii::t('app', 'Профиль') ?></a></li>
                                <li><a href="/profile/stats"><?= \Yii::t('app', 'Статистика') ?></a></li>
                                <li><a href="/profile/info"><?= \Yii::t('app', 'Заявки на участие') ?></a></li>
                                <li><a href="/figures/send-result"><?= \Yii::t('app', 'Отправить результат') ?></a></li>
                                <li><a href="/profile/change-class"><?= \Yii::t('app', 'Смена класса') ?></a></li>
                                <li><a href="/site/logout"><?= \Yii::t('app', 'Выход') ?></a></li>
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
									<?= \yii\bootstrap\Html::a(\Yii::t('app', 'Показать все уведомления'), ['/notices/all']) ?>
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