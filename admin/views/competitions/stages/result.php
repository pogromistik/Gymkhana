<?php
use common\models\Championship;

/**
 * @var \yii\web\View                $this
 * @var \common\models\Stage         $stage
 * @var \common\models\Participant[] $participants
 * @var integer                      $orderBy
 */

$championship = $stage->championship;
$this->title = $championship->title . ', ' . $stage->title . ': итоги';
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$championship->groupId], 'url' => ['/competitions/championships/index', 'groupId' => $championship->groupId]];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/championships/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['view', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = 'Итоги';
$place = 1;
$newClasses = $stage->getParticipantsForRaces()->andWhere(['not', ['newAthleteClassId' => null]])
	->andWhere(['newAthleteClassStatus' => \common\models\Participant::NEW_CLASS_STATUS_NEED_CHECK])->all();
?>

<div class="row pb-10">
    <div class="col-sm-6">
		<?= \yii\helpers\Html::a('Скачать в xls', ['/competitions/xls/stage-results', 'stageId' => $stage->id], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="col-sm-6 text-right">
		<?= \yii\helpers\Html::a('Добавить видео заездов', ['/competitions/stages/add-video', 'stageId' => $stage->id], ['class' => 'btn btn-warning']) ?>
    </div>
</div>

<?php if ($stage->class) { ?><h4>Класс соревнования: <?= $stage->classModel->title ?></h4><?php } ?>
<?php if ($stage->referenceTime) { ?><h4>Эталонное время: <?= $stage->referenceTimeHuman ?></h4><?php } ?>

<?php if ($newClasses) { ?>
    <div class="text-right newClass">
        <div class="pb-10">
            <a class="btn btn-danger getRequest" href="#"
               data-action="/competitions/participants/cancel-all-classes"
               data-id="<?= $stage->id ?>" title="Отменить">
                Отменить все новые неподтверждённые классы
            </a>
            <a class="btn btn-success getRequest" href="#"
               data-action="/competitions/participants/approve-all-classes"
               data-id="<?= $stage->id ?>" title="Подтвердить">
                Подтвердить все новые классы
            </a>
        </div>
    </div>
<?php } ?>

<?= \yii\helpers\Html::beginForm(['result', 'stageId' => $stage->id], 'get') ?>
<?= \yii\helpers\Html::dropDownList('orderBy', $orderBy, \common\models\Stage::$orderByTitles,
	['prompt' => 'Сортировка', 'onchange' => 'this.form.submit()', 'class' => 'form-control']) ?>
<button type="submit" style="visibility: hidden;" title="Сохранить"></button>
<?= \yii\helpers\Html::endForm() ?>

<div class="color-div out-participant"></div>
- участники вне зачета
<table class="table results">
    <thead>
    <tr>
        <th class="<?= !$orderBy ? 'bg-gray' : '' ?>">Место вне класса</th>
        <th>Группа</th>
        <th class="<?= ($orderBy == \common\models\Stage::ORDER_BY_ATHLETE_CLASS) ? 'bg-gray' : '' ?>">Место в группе
        </th>
        <th>№</th>
        <th>Участник</th>
        <th>Мотоцикл</th>
        <th>Попытка</th>
        <th>Время</th>
        <th>Штраф</th>
        <th>Лучшее время</th>
        <th>Класс награждения</th>
        <th class="<?= ($orderBy == \common\models\Stage::ORDER_BY_INTERNAL_CLASS) ? 'bg-gray' : '' ?>">Место в классе
            награждения
        </th>
        <th>Рейтинг</th>
        <th>Новый класс</th>
        <th>Баллы за этап</th>
    </tr>
    </thead>
    <tbody>
	<?php foreach ($participants as $participant) {
		$athlete = $participant->athlete;
		$times = $participant->times;
		$first = null;
		if ($times) {
			$first = reset($times);
		}
		$class = 'default';
		if ($participant->status == \common\models\Participant::STATUS_OUT_COMPETITION) {
			$class = 'out-participant';
		}
		
		$athleteCssClass = 'default';
		$internalCssClass = 'default';
		if ($participant->athleteClassId) {
			$participantClass = $participant->athleteClass;
			if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($participantClass->title, 'UTF-8')])) {
				$athleteCssClass = 'result-' . \common\models\Athlete::$classesCss[mb_strtoupper($participantClass->title, 'UTF-8')];
			}
		}
		if ($participant->internalClassId) {
			$internalCssClass = 'bg-' . $participant->internalClassId % 10;
		}
		?>
        <tr class="<?= $class ?>">
            <td rowspan="<?= $stage->countRace ?>" class="<?= !$orderBy ? 'bg-gray' : '' ?>">
				<?= $participant->place ?></td>
            <td rowspan="<?= $stage->countRace ?>"
                class="<?= $athleteCssClass ?>"><?= $participant->athleteClassId ? $participant->athleteClass->title : null ?></td>
            <td rowspan="<?= $stage->countRace ?>"
                class="<?= ($orderBy == \common\models\Stage::ORDER_BY_ATHLETE_CLASS) ? 'bg-gray' : '' ?>">
				<?= $participant->placeOfAthleteClass ?></td>
            <td rowspan="<?= $stage->countRace ?>"><?= $participant->number ?></td>
            <td rowspan="<?= $stage->countRace ?>"><?= $athlete->getFullName() ?><br><?= $athlete->city->title ?></td>
            <td rowspan="<?= $stage->countRace ?>"><?= $participant->motorcycle->getFullTitle() ?></td>
			<?php if ($first) { ?>
                <td>1.</td>
                <td>
                    <?php if (\Yii::$app->user->can('developer')) {
                        $tmeForHuman = \yii\helpers\Html::a($first->timeForHuman, ['/competitions/developer/logs',
                            'modelClass' => \common\models\Time::class, 'modelId' => $first->id]);
                    } else {
                        $tmeForHuman = $first->timeForHuman;
                    } ?>
					<?php if ($first->isFail) { ?>
                        <strike><?= $tmeForHuman ?></strike>
					<?php } else { ?>
						<?= $tmeForHuman ?>
					<?php } ?>
                    <?php if ($first->videoLink) { ?>
                        <a href="<?= $first->videoLink ?>" class="fa fa-youtube" target="_blank"></a>
                    <?php } ?>
                </td>
                <td><?= $first->fine ?></td>
			<?php } else { ?>
                <td>1.</td>
                <td></td>
                <td></td>
			<?php } ?>
            <td rowspan="<?= $stage->countRace ?>"><?= $participant->humanBestTime ?></td>
            <td rowspan="<?= $stage->countRace ?>"
                class="<?= $internalCssClass ?>"><?= $participant->internalClass ? $participant->internalClass->title : null ?></td>
            <td rowspan="<?= $stage->countRace ?>"
                class="<?= ($orderBy == \common\models\Stage::ORDER_BY_INTERNAL_CLASS) ? 'bg-gray' : '' ?>">
				<?= $participant->placeOfClass ?></td>
            <td rowspan="<?= $stage->countRace ?>"><?= $participant->percent ?>%</td>
            <td rowspan="<?= $stage->countRace ?>" class="newClass">
				<?php if ($participant->newAthleteClassId) { ?>
					<?= $participant->newAthleteClass->title ?>
					<?php if ($participant->newAthleteClassStatus == \common\models\Participant::NEW_CLASS_STATUS_NEED_CHECK) { ?>
                        <br>
                        <a class="btn btn-danger getRequest" href="#"
                           data-action="/competitions/participants/cancel-class"
                           data-id="<?= $participant->id ?>" title="Отменить">
                            <span class="fa fa-remove"></span>
                        </a>
                        <a class="btn btn-success getRequest" href="#"
                           data-action="/competitions/participants/approve-class"
                           data-id="<?= $participant->id ?>" title="Подтвердить">
                            <span class="fa fa-check"></span>
                        </a>
					<?php } ?>
				<?php } ?>
            </td>
            <td rowspan="<?= $stage->countRace ?>"><?= $championship->useMoscowPoints ? $participant->pointsByMoscow : $participant->points ?></td>
        </tr>
		<?php
		$attempt = 1;
		while ($attempt++ < $stage->countRace) {
			$next = null;
			if ($times) {
				$next = next($times);
			}
			?>
            <tr class="<?= $class ?>">
                <td><?= $attempt ?>.</td>
				<?php if ($next) { ?>
                    <td>
	                    <?php if (\Yii::$app->user->can('developer')) {
		                    $tmeForHuman = \yii\helpers\Html::a($next->timeForHuman, ['/competitions/developer/logs',
			                    'modelClass' => \common\models\Time::class, 'modelId' => $next->id]);
	                    } else {
		                    $tmeForHuman = $next->timeForHuman;
	                    } ?>
						<?php if ($next->isFail) { ?>
                            <strike><?= $tmeForHuman ?></strike>
						<?php } else { ?>
							<?= $tmeForHuman ?>
						<?php } ?>
	                    <?php if ($next->videoLink) { ?>
                            <a href="<?= $next->videoLink ?>" class="fa fa-youtube" target="_blank"></a>
	                    <?php } ?>
                    </td>
                    <td><?= $next->fine ?></td>
				<?php } else { ?>
                    <td></td>
                    <td></td>
				<?php } ?>
            </tr>
			<?php
		}
		?>
	<?php } ?>
    </tbody>
</table>
