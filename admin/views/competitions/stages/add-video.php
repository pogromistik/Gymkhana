<?php
use common\models\Championship;
use dosamigos\editable\Editable;

/**
 * @var \yii\web\View                $this
 * @var \common\models\Stage         $stage
 * @var \common\models\Participant[] $participants
 */

$championship = $stage->championship;
$this->title = $championship->title . ', ' . $stage->title . ': добавление видео';
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$championship->groupId], 'url' => ['/competitions/championships/index', 'groupId' => $championship->groupId]];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/championships/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['view', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = ['label' => 'Итоги', 'url' => ['result', 'stageId' => $stage->id]];
$this->params['breadcrumbs'][] = 'Добавление видео';
$place = 1;
?>

<table class="table results">
    <thead>
    <tr>
        <th>№</th>
        <th>Участник</th>
        <th>Мотоцикл</th>
        <th>Попытка</th>
        <th>Время</th>
        <th>Штраф</th>
        <th>Видео</th>
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
		?>
        <tr class="<?= $class ?>">
            <td rowspan="<?= $stage->countRace ?>"><?= $participant->number ?></td>
            <td rowspan="<?= $stage->countRace ?>"><?= $athlete->getFullName() ?><br><?= $athlete->city->title ?></td>
            <td rowspan="<?= $stage->countRace ?>"><?= $participant->motorcycle->getFullTitle() ?></td>
			<?php if ($first) { ?>
                <td>1.</td>
                <td>
					<?php if ($first->isFail) { ?>
                        <strike><?= $first->timeForHuman ?></strike>
					<?php } else { ?>
						<?= $first->timeForHuman ?>
					<?php } ?>
                </td>
                <td><?= $first->fine ?></td>
                <td>
					<?= Editable::widget([
						'name'          => 'videoLink',
						'value'         => $first->videoLink,
						'type'          => 'text',
						'mode'          => 'inline',
						'url'           => 'add-video-link',
						'clientOptions' => [
							'pk'        => $first->id,
							'value'     => $first->videoLink,
							'placement' => 'right',
						]
					]); ?>
                </td>
			<?php } else { ?>
                <td>1.</td>
                <td></td>
                <td></td>
                <td></td>
			<?php } ?>
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
						<?php if ($next->isFail) { ?>
                            <strike><?= $next->timeForHuman ?></strike>
						<?php } else { ?>
							<?= $next->timeForHuman ?>
						<?php } ?>
                    </td>
                    <td><?= $next->fine ?></td>
                    <td><?= Editable::widget([
							'name'          => 'videoLink',
							'value'         => $next->videoLink,
							'type'          => 'text',
							'mode'          => 'inline',
							'url'           => 'add-video-link',
							'clientOptions' => [
								'pk'          => $next->id,
								'placeholder' => $next->videoLink,
								'placement'   => 'right',
							],
							'options'       => [
								'placeholder' => 'добавить видео',
							]
						]); ?>
                    </td>
				<?php } else { ?>
                    <td></td>
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
