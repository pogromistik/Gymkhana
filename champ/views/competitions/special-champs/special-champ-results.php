<?php
/**
 * @var \yii\web\View                 $this
 * @var \common\models\SpecialChamp   $championship
 * @var array                         $results
 * @var \common\models\SpecialStage[] $stages
 * @var \common\models\Athlete        $athlete
 * @var \common\models\SpecialStage[] $outOfChampStages
 */
$this->title = \Yii::t('app', 'Результаты: {title}', [
        'title' => $championship->getTitle()
    ]);
?>
<h3><?= $this->title ?></h3>

<div class="about pt-10">
    <div class="card-box">
        <h4><?= \Yii::t('app', 'Формула, по которой производился расчёт баллов за каждый этап:') ?></h4>
        Х=1809*k<sup>2</sup>+7885.7*k+8076.7,<br>
        <?= \Yii::t('app', 'Где k - отношение времени спортсмена к эталонному времени трассы') ?><br>
        <?= \Yii::t('app', 'Баллы начисляются только спортсменам с рейтингом <= 160%') ?>
    </div>
	
	<?php if ($outOfChampStages) { ?>
        <div class="card-box">
            <div class="pt-10 pb-10">
                <b><?= \Yii::t('app', 'Следующие этапы проводились вне зачёта:') ?></b><br>
                <ul>
			        <?php foreach ($outOfChampStages as $outOfChampStage) { ?>
                        <li><?= $outOfChampStage->getTitle() ?></li>
			        <?php } ?>
                </ul>
		        <?= \Yii::t('app', 'Баллы за эти этапы не учитываются при подсчёте итоговой суммы. В таблице такие этапы выделены серым цветом.') ?>
            </div>
        </div>
	<?php } ?>
</div>

<div class="pt-10 card-box">
    <h4><?= \Yii::t('app', 'Результаты:') ?></h4>
    <table class="table table-responsive">
        <thead>
        <tr>
            <th><?= \Yii::t('app', 'Место') ?></th>
            <th><?= \Yii::t('app', 'Класс') ?></th>
            <th><?= \Yii::t('app', 'Спортсмен') ?></th>
			<?php foreach ($stages as $stage) {
				$class = '';
				if ($stage->outOfCompetitions) {
					$class = 'gray-column';
				}
				?>
                <th class="<?= $class ?>"><?= \yii\helpers\Html::a($stage->title, ['/competitions/special-stage', 'id' => $stage->id]) ?></th>
			<?php } ?>
            <th><?= \Yii::t('app', 'Итого') ?></th>
        </tr>
        </thead>
        <tbody>
		<?php
		$place = 0;
		$prevPoints = 0;
		$prevCount = 1;
		foreach ($results as $result) {
			?>
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
                <td><?= $athlete->athleteClassId ? $athlete->athleteClass->title : null ?></td>
                <td>
					<?= \yii\helpers\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                    <br>
					<?= \common\helpers\TranslitHelper::translitCity($athlete->city->title) ?>
                </td>
				<?php foreach ($stages as $stage) {
					$class = '';
					if ($stage->outOfCompetitions) {
						$class = 'gray-column';
					}
					?>
					<?php if (isset($result['stages'][$stage->id])) { ?>
                        <td class="<?= $class ?>"><?= $result['stages'][$stage->id] ?></td>
					<?php } else { ?>
                        <td class="<?= $class ?>">0</td>
					<?php } ?>
				<?php } ?>
                <td><?= $result['points'] ?></td>
            </tr>
		<?php } ?>
        </tbody>
    </table>
</div>
