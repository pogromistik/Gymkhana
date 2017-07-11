<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View        $this
 * @var array                $dates
 * @var array                $notDate
 * @var \common\models\Stage $stage
 * @var array                $events
 */
?>
<h2><?= \Yii::t('app', 'Расписание соревнований') ?></h2>

<div class="result-scheme active">
    <div class="change-type">
        <a class="change-result-scheme"><?= \Yii::t('app', 'Посмотреть список') ?></a>
    </div>
	<?= \yii2fullcalendar\yii2fullcalendar::widget([
		'events'        => $events,
		'options'       => [
			'lang' => 'ru',
		],
		'clientOptions' => [
			'language' => 'ru'
		],
		'header'        => [
			'right'  => 'title',
			'center' => '',
			'left'   => 'prevYear,prev,next,nextYear today',
		]
	]);
	?>
</div>
<div class="result-scheme">
    <div class="change-type">
        <a href="#" class="change-result-scheme"><?= \Yii::t('app', 'Посмотреть календарь') ?></a>
    </div>
    <div class="schedule">
        <table class="table table-striped">
			<?php if ($notDate) { ?>
                <tr>
                    <th>
                        <div class="month">
                            <?= \Yii::t('app', 'Дата проведения не установлена') ?>
                        </div>
                    </th>
                </tr>
				<?php foreach ($notDate as $stage) { ?>
                    <tr>
                        <td>
                            <div class="row item">
                                <div class="col-md-2 col-sm-3 col-xs-4">
                                </div>
                                <div class="col-md-10 col-sm-9 col-xs-8">
									<?= Html::a($stage->championship->title . ': ' . $stage->title . ', ' . $stage->city->title,
										['/competitions/stage', 'id' => $stage->id]) ?>
                                </div>
                            </div>
                        </td>
                    </tr>
				<?php } ?>
			<?php } ?>
			
			<?php if ($dates) { ?>
				<?php foreach ($dates as $date => $stages) { ?>
                    <tr>
                        <th>
                            <div class="month">
								<?= \common\models\HelpModel::$month[date("n", $date)] ?>
                            </div>
                        </th>
                    </tr>
					<?php foreach ($stages as $stage) { ?>
                        <tr>
                            <td>
                                <div class="row item">
                                    <div class="col-md-2 col-sm-3 col-xs-4">
										<?= date("d.m.Y", $stage->dateOfThe) ?>
                                    </div>
                                    <div class="col-md-10 col-sm-9 col-xs-8">
										<?= Html::a($stage->championship->title . ': ' . $stage->title . ', ' . $stage->city->title,
											['/competitions/stage', 'id' => $stage->id]) ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
					<?php } ?>
				<?php } ?>
			<?php } ?>
        </table>
    </div>
</div>