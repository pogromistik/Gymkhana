<?php
/**
 * @var \yii\web\View                $this
 * @var \common\models\Stage         $stage
 * @var \common\models\Participant[] $participants
 */

$this->title = $stage->championship->title . ', ' . $stage->title . ': итоги';
$this->params['breadcrumbs'][] = ['label' => 'Чемпионаты', 'url' => ['/competitions/championships/index']];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/championships/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['view', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = 'Итоги';
$place = 1;
?>

<table class="table results">
	<thead>
	<tr>
		<th>Место</th>
		<th>Класс</th>
		<th>№</th>
		<th>Участник</th>
		<th>Мотоцикл</th>
		<th>Попытка</th>
		<th>Время</th>
		<th>Штраф</th>
		<th>Лучшее время</th>
        <th>Место в классе</th>
        <th>Рейтинг</th>
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
		?>
		<tr>
			<td rowspan="<?=$stage->countRace?>"><?= $place++ ?></td>
			<td rowspan="<?=$stage->countRace?>"><?= $participant->internalClass ? $participant->internalClass->title : null ?></td>
			<td rowspan="<?=$stage->countRace?>"><?= $participant->number ?></td>
			<td rowspan="<?=$stage->countRace?>"><?= $athlete->getFullName() ?><br><?= $athlete->city->title ?></td>
			<td rowspan="<?=$stage->countRace?>"><?= $participant->motorcycle->getFullTitle() ?></td>
            <?php if ($first) { ?>
                <td>1.</td>
                <td><?= $first->timeForHuman ?></td>
                <td><?= $first->fine ?></td>
            <?php } else { ?>
                <td>1.</td>
                <td></td>
                <td></td>
            <?php } ?>
			<td rowspan="<?=$stage->countRace?>"><?= $participant->humanBestTime ?></td>
            <td rowspan="<?=$stage->countRace?>"><?= $participant->placeOfClass ?></td>
            <td rowspan="<?=$stage->countRace?>"><?= $participant->percent ?>%</td>
		</tr>
		<?php
		$attempt = 1;
		while ($attempt++ < $stage->countRace) {
			$next = null;
		    if ($times) {
			    $next = next($times);
		    }
			?>
            <tr>
                <td><?= $attempt ?>.</td>
	            <?php if ($next) { ?>
                    <td><?= $next->timeForHuman ?></td>
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
