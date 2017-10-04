<?php
/**
 * @var \yii\web\View       $this
 * @var \common\models\Work $model
 * @var int                 $hours
 * @var int                 $mins
 * @var int                 $secs
 * @var int                 $endHours
 * @var int                 $endMins
 * @var int                 $endSecs
 * @var int                 $endDay
 * @var int                 $endMonth
 */
$this->title = 'Ошибка!';
?>

    <div class="work-page">
        <h1>Ошибка!</h1>
		<?= $model->text ?>

        <div id="mytimer">
            <h3>сайт возобновит работу не позже, чем через:</h3>


            <div class="time-blocks">
                <div class="down-time">
                    <div class="item" id="hours">
						<?= $hours ?>
                    </div>
                    <div>
                        Часов
                    </div>
                </div>

                <div class="points">:</div>

                <div class="down-time">
                    <div class="item" id="mins">
						<?= $mins ?>
                    </div>
                    <div>
                        Минут
                    </div>
                </div>

                <div class="points">.</div>

                <div class="down-time">
                    <div class="item" id="secs">
						<?= $secs ?>
                    </div>
                    <div>
                        Секунд
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$js = <<<EOF
countDown($endSecs, $endMins, $endHours, $endDay, $endMonth);
EOF;

$this->registerJs($js);