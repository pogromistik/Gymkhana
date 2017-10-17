<?php
use common\models\SpecialChamp;

/**
 * @var \yii\web\View                 $this
 * @var SpecialChamp                  $championship
 * @var array                         $results
 * @var \common\models\Athlete        $athlete
 * @var \common\models\SpecialStage[] $stages
 */
$this->title = 'Результаты: ' . $championship->title;
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $championship->title, 'url' => ['view', 'id' => $championship->id]];
$this->params['breadcrumbs'][] = 'Результаты';
?>

<h4>Система начисления баллов:</h4>
<table class="table table-bordered">
    <thead>
    <tr>
        <td><b>Место</b></td>
        <?php foreach (\common\models\SpecialStage::$points as $place => $points) { ?>
            <td><?= $place ?></td>
        <?php } ?>
    </tr>
    <tr>
        <td><b>Баллы</b></td>
	    <?php foreach (\common\models\SpecialStage::$points as $place => $points) { ?>
            <td><?= $points ?></td>
	    <?php } ?>
    </tr>
    </thead>
</table>

<h4>Итоги:</h4>
<table class="table">
    <thead>
    <tr>
        <th>Место</th>
        <th>Класс</th>
        <th>Спортсмен</th>
		<?php foreach ($stages as $stage) { ?>
            <th><?= $stage->title ?></th>
		<?php } ?>
        <th>Итого</th>
    </tr>
    </thead>
    <tbody>
	<?php
	$place = 0;
	$prevPoints = 0;
	$prevCount = 1;
	foreach ($results as $result) { ?>
		<?php $athlete = $result['athlete'] ?>
		<?php
		if ($result['points'] > 0 && $result['points'] == $prevPoints) {
			$prevCount += 1;
		} else {
			$place += $prevCount;
			$prevCount = 1;
		}
		$prevPoints = $result['points'];
		?>
        <tr>
            <td><?= $place ?></td>
            <td><?= $athlete->athleteClass->title ?></td>
            <td>
				<?= $athlete->getFullName() ?>
                <br>
				<small><?= $athlete->city->title ?></small>
            </td>
			<?php foreach ($stages as $stage) { ?>
				<?php if (isset($result['stages'][$stage->id])) { ?>
                    <td><?= $result['stages'][$stage->id] ?></td>
				<?php } else { ?>
                    <td>0</td>
				<?php } ?>
			<?php } ?>
            <td><?= $result['points'] ?></td>
        </tr>
	<?php } ?>
    </tbody>
</table>
