<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php if (\Yii::$app->user->can('competitions')) { ?>
    <li class="competitions active">
        <a href="#"><i class="fa fa-motorcycle fa-fw"></i> СОРЕВНОВАНИЯ<span
                    class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
            <li>
                <a href="<?= Url::to(['/competitions/help/years']) ?>"> Года</a>
            </li>
            <li>
                <a href="<?= Url::to(['/competitions/help/cities']) ?>"> Города</a>
            </li>
            <li class="level-2">
                <a href="#"> Калькулятор<span
                            class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?= Url::to(['/competitions/help/time-calculate']) ?>"> Эталонное
                            время</a></li>
                    <li><a href="<?= Url::to(['/competitions/help/result-calculate']) ?>"> Результат
                            спортсмена</a></li>
                </ul>
            </li>
			<?php if (\Yii::$app->user->can('globalWorkWithCompetitions')) { ?>
                <li>
                    <a data-addr="/competitions/classes"
                       href="<?= Url::to(['/competitions/classes/index']) ?>"> Классы
                        спортсменов</a>
                </li>
				<?php if (\Yii::$app->user->can('developer')) { ?>
                    <li>
                        <a data-addr="/competitions/additional"
                           href="<?= Url::to(['/competitions/additional/che-scheme']) ?>"> Классы
                            награждения</a>
                    </li>
				<?php } ?>
                <li>
                    <a href="<?= Url::to(['/competitions/additional/points']) ?>"> Баллы для
                        чемпионатов</a>
                </li>
			<?php } ?>
			<?php if (\common\helpers\UserHelper::fromRegion('Московская область')) { ?>
                <li>
                    <a href="<?= Url::to(['/competitions/moscow-points/index']) ?>"> Баллы для
                        Москвы</a>
                </li>
			<?php } ?>
			<?php if (\Yii::$app->user->can('projectAdmin')) { ?>
                <li>
                    <a data-addr="/competitions/news"
                       href="<?= Url::to(['/competitions/news/index']) ?>"> Новости</a>
                </li>
                <li>
                    <a data-addr="/competitions/documents"
                       href="<?= Url::to(['/competitions/documents/index']) ?>"> Документы</a>
                </li>
			<?php } ?>
            <li>
                <a data-addr="/competitions/athlete"
                   href="<?= Url::to(['/competitions/athlete/index']) ?>"> Спортсмены</a>
            </li>
            <li class="level-2 active">
                <a href="#"> Чемпионаты<span
                            class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
					<?php foreach (\common\models\Championship::$groupsTitle as $groupId => $title) { ?>
                        <li><?= Html::a($title, ['/competitions/championships/index', 'groupId' => $groupId]) ?></li>
					<?php } ?>
					<?php if (\Yii::$app->user->can('changeSpecialChamps')) { ?>
                        <li>
							<?= Html::a('Особые чемпионаты', ['/competitions/special-champ/index']) ?>
                        </li>
					<?php } ?>
                </ul>
            </li>
			<?php if (\Yii::$app->user->can('projectOrganizer')) { ?>
                <li>
                    <a data-addr="/competitions/figures"
                       href="<?= Url::to(['/competitions/figures/index']) ?>"> Фигуры</a>
                </li>
			<?php } ?>
			<?php if (\Yii::$app->user->can('globalWorkWithCompetitions')) { ?>
                <li>
                    <a href="#"> Уведомления<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a data-addr="/competitions/notice/"
                               href="<?= Url::to(['/competitions/notice/index']) ?>"> Регионам</a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/competitions/notice/one']) ?>">
                                Человеку</a>
                        </li>
                    </ul>
                </li>
			<?php } elseif (\Yii::$app->user->can('projectAdmin')) { ?>
                <li>
                    <a data-addr="/competitions/notice"
                       href="<?= Url::to(['/competitions/notice/index']) ?>"> Отправить
                        уведомление</a>
                </li>
			<?php } ?>
			<?php if (\Yii::$app->user->can('projectAdmin')) { ?>
                <li>
                    <a href="<?= Url::to(['/competitions/users/index']) ?>"> Управление
                        пользователями</a>
                </li>
			<?php } ?>
			<?php if (\Yii::$app->user->can('globalWorkWithCompetitions')) { ?>
                <li class="level-2">
                    <a href="#"> История<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?= Url::to(['/competitions/additional/l-k-requests']) ?>">
                                Личный кабинет</a></li>
                        <li><a href="<?= Url::to(['/competitions/additional/figures-requests']) ?>">
                                Базовые фигуры</a></li>
                        <li><a href="<?= Url::to(['/competitions/additional/stages-requests']) ?>">
                                Заявки на этап</a></li>
                        <li><a href="<?= Url::to(['/competitions/additional/classes-request']) ?>">
                                Заявки на изменение класса</a></li>
                        <li><a href="<?= Url::to(['/competitions/additional/messages']) ?>">
                                Отправленные письма</a></li>
                    </ul>
                </li>
			<?php } ?>
			<?php if (\Yii::$app->user->can('competitions')) { ?>
                <li>
                    <a href="<?= Url::to(['/competitions/additional/stats']) ?>"> Статистика</a>
                </li>
			<?php } ?>
			<?php if (\Yii::$app->user->can('projectOrganizer')) { ?>
                <li>
                    <a href="<?= Url::to(['/competitions/athlete/change-class']) ?>">Изменить класс
                        спортсмену</a>
                </li>
			<?php } ?>
			<?php if (\Yii::$app->user->can('canSendMessages')) { ?>
                <li>
                    <a href="#"> Отправка сообщений<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a data-addr="/competitions/notice/"
                               href="<?= Url::to(['/competitions/additional/message',
								   'type' => \common\models\Message::TYPE_TO_PARTICIPANTS]) ?>">
                                Участникам этапа</a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/competitions/additional/message',
								'type' => \common\models\Message::TYPE_TO_ATHLETES]) ?>">
                                Спортсменам</a>
                        </li>
                    </ul>
                </li>
			<?php } ?>
            <li>
                <a href="<?= Url::to(['/competitions/additional/mails']) ?>"> Текста писем</a>
            </li>
        </ul>
    </li>
<?php } ?>