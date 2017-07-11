<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View               $this
 * @var \common\models\FigureTime[] $figuresResult
 * @var \common\models\Figure       $figure
 */
?>

    <h2>Результаты по фигуре <?= $figure->title ?></h2>

    <div class="figures pt-10">
		<?php if (!$figuresResult) { ?>
            У вас не добавлено ни одного результата для этой фигуры.
		<?php } else { ?>
            <small>В таблице указано время с учетом штрафа, но при наличии штраф указывается. Т.е. если
                вы проехали за 20сек +1 штраф, в таблице выведется 00:21.00 (+1).
            </small>
            <table class="table table-bordered">
                <tr>
                    <td><b>Дата заезда</b></td>
                    <td>
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <b>Итоговое время</b>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <b>Рейтинг</b>
                            </div>
                        </div>
                    </td>
                    <td><b>Класс старый/новый</b></td>
                </tr>
				<?php foreach ($figuresResult as $result) { ?>
                    <tr>
                        <td><?= $result->dateForHuman ?></td>
                        <td>
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
									<?= $result->resultTimeForHuman ?>
									<?php if ($result->fine) { ?>
                                        <small> (<?= $result->timeForHuman ?> +<?= $result->fine ?>)</small>
									<?php } ?>
									<?php if ($result->recordType && $result->recordStatus == \common\models\FigureTime::NEW_RECORD_APPROVE) { ?>
										<?= \yii\bootstrap\Html::img('/img/crown.png', [
											'title' => \Yii::t('app', \common\models\FigureTime::$recordsTitle[$result->recordType]) . '!',
											'alt'   => \Yii::t('app', \common\models\FigureTime::$recordsTitle[$result->recordType]) . '!'
										]) ?>
									<?php } ?>
                                </div>
                                <div class="col-sm-6 col-xs-12">
									<?= $result->percent ?>%
                                </div>
                            </div>
                        </td>
                        <td><?= $result->athleteClass ? $result->athleteClass->title : 'не установлен' ?>/
							<?= $result->newAthleteClass ? $result->newAthleteClass->title : 'не изменился' ?></td>
                    </tr>
				<?php } ?>
            </table>
		<?php } ?>
    </div>

<?= Html::a('Вернуться на страницу статистики', ['/profile/stats']) ?>