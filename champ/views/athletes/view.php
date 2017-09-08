<?php
use yii\bootstrap\Html;

/**
 * @var \common\models\Athlete        $athlete
 * @var array                         $figuresResult
 * @var \common\models\FigureTime     $result
 * @var \common\models\Figure         $figure
 * @var \common\models\ClassHistory[] $history
 * @var \common\models\Participant[]  $participants
 */
?>


<div class="athlete">
	<?php if ($athlete->photo) { ?>
        <div class="inline-block">
            <div class="img">
				<?= Html::img(\Yii::getAlias('@filesView') . $athlete->photo) ?>
            </div>
        </div>
	<?php } ?>
    <div class="inline-block">
        <h3><?= $athlete->getFullName() ?><?php if ($athlete->number) { ?>, №<?= $athlete->number ?><?php } ?></h3>
        <div class="info">
            <div class="item">
                <b><?= \Yii::t('app', 'Город') ?>: </b><?= $athlete->city->title ?>, <?= $athlete->region->title ?>
            </div>
			<?php if ($athlete->athleteClassId) { ?>
                <div class="item">
                    <b><?= \Yii::t('app', 'Класс') ?>: </b><?= $athlete->athleteClass->title ?>
                </div>
			<?php } ?>
        </div>
        <div class="motorcycles pt-10">
            <h4><?= \Yii::t('app', 'Мотоциклы') ?>:</h4>
			<?php /** @var \common\models\Motorcycle[] $motorcycles */
			$motorcycles = $athlete->getMotorcycles()->andWhere(['status' => \common\models\Motorcycle::STATUS_ACTIVE])->all();
			foreach ($motorcycles as $motorcycle) { ?>
                <div class="item"><?= $motorcycle->getFullTitle() ?></div>
			<?php } ?>
        </div>
    </div>

    <div class="figures pt-10">
        <h4>
			<?= \Yii::t('app', 'Результаты базовых фигур') ?><br>
            <small><?= \Yii::t('app', 'представлены только лучшие результаты') ?></small>
            <br>
            <span class="small">
                <small><?= \Yii::t('app', 'для просмотра прогресса по фигуре нажмите на время') ?></small>
            </span>
        </h4>
		<?php if (!$figuresResult) { ?>
			<?= \Yii::t('app', 'Информация отсутствует') ?>
		<?php } else { ?>
            <table class="table table-bordered">
				<?php foreach ($figuresResult as $data) { ?>
                    <tr>
						<?php
						$figure = $data['figure'];
						$result = $data['result'];
						?>
                        <td><?= $result->dateForHuman ?></td>
                        <td><?= Html::a($figure->title, ['/competitions/figure', 'id' => $figure->id], ['target' => '_blank']) ?></td>
                        <td class="show-pk"><?= $result->motorcycle->getFullTitle() ?></td>
                        <td>
							<?= \yii\helpers\Html::a($result->resultTimeForHuman, ['/competitions/progress',
								'figureId' => $figure->id, 'athleteId' => $athlete->id]) ?>
							<?php if ($result->fine) { ?>
                                <small> (<?= $result->timeForHuman ?> +<?= $result->fine ?>)</small>
							<?php } ?>
							<?php if ($result->recordType && $result->recordStatus == \common\models\FigureTime::NEW_RECORD_APPROVE) { ?>
								<?= \yii\bootstrap\Html::img('/img/crown.png', [
									'title' => \common\models\FigureTime::$recordsTitle[$result->recordType] . '!',
									'alt'   => \common\models\FigureTime::$recordsTitle[$result->recordType] . '!'
								]) ?>
							<?php } ?>
                            <div class="show-mobile">
                                <small><?= $result->motorcycle->getFullTitle() ?></small>
                            </div>
                        </td>
                        <td><?= $result->actualPercent ? $result->actualPercent : $result->percent ?>%</td>
                    </tr>
				<?php } ?>
            </table>
		<?php } ?>
    </div>
    
    <?php if ($participants) { ?>
    <div class="history pt-10">
        <h4>
           <?= \Yii::t('app', 'Участие в этапах') ?><br>
            <small><?= \Yii::t('app', 'показано не более 30 последних записей') ?></small>
        </h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th><?= \Yii::t('app', 'Этап') ?></th>
                    <th><?= \Yii::t('app', 'Мотоцикл') ?></th>
                    <th><?= \Yii::t('app', 'Рейтинг') ?></th>
                    <th><?= \Yii::t('app', 'Место в абсолюте') ?></th>
                </tr>
				<?php foreach ($participants as $participant) { ?>
                    <tr>
                        <td>
							<?php $stage = $participant->stage; ?>
							<?= Html::a($stage->title, ['/competitions/stage', 'id' => $stage->id]) ?><br>
                            <small><?= $stage->dateOfThe ? $stage->dateOfTheHuman : '' ?></small>
                        </td>
                        <td><?= $participant->motorcycle->getFullTitle() ?></td>
                        <td>
	                        <?php if ($participant->percent) {
		                        echo $participant->percent . '%';
	                        } else {
		                        if ($stage->referenceTime) {
			                        ?>
                                    <span class="fa fa-remove remove"></span>
			                        <?php
		                        } else {
		                            ?>
                                    <span class="green">...</span>
                            <?php
                                }
	                        } ?>
                        <td>
							<?php if ($participant->place) {
								echo $participant->place;
							} else {
								if ($stage->referenceTime) {
									?>
                                    <span class="fa fa-remove remove"></span>
									<?php
								} else {
									?>
                                    <span class="green">...</span>
									<?php
								}
							} ?>
                        </td>
                    </tr>
				<?php } ?>
            </table>
        </div>
    <?php } ?>
	
	<?php if ($history) { ?>
        <div class="history pt-10">
            <h4>
				<?= \Yii::t('app', 'История переходов между классами') ?><br>
                <small><?= \Yii::t('app', 'показано не более 15 последних записей') ?></small>
            </h4>
            <table class="table">
                <tr>
                    <th><?= \Yii::t('app', 'Дата') ?></th>
                    <th><?= \Yii::t('app', 'Старый класс') ?></th>
                    <th><?= \Yii::t('app', 'Новый класс') ?></th>
                    <th><?= \Yii::t('app', 'Событие') ?></th>
                </tr>
				<?php foreach ($history as $item) { ?>
                    <tr>
                        <td><?= $item->dateForHuman ?></td>
                        <td><?= $item->oldClassId ? $item->oldClass->title : '' ?></td>
                        <td><?= $item->newClass->title ?></td>
                        <td><?= $item->event ?></td>
                    </tr>
				<?php } ?>
            </table>
        </div>
	<?php } ?>
</div>

<a href="<?= \yii\helpers\Url::to(['/athletes/list']) ?>"><?= \Yii::t('app', 'Вернуться к спортсменам') ?></a>