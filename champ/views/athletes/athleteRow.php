<?php
use yii\bootstrap\Html;
/**
 * @var \common\models\Athlete $model
 * @var \common\models\Motorcycle[] $motorcycles
 */
$athlete = $model;
?>

<div class="col-xs-12 col-sm-6 col-lg-4 col-bg-3 item">
	<a href="/athletes/view?id=<?=$athlete->id?>">
		<div class="card">
			<div class="img">
				<?php if ($athlete->photo) { ?>
					<?= Html::img(\Yii::getAlias('@filesView') . $athlete->photo) ?>
				<?php } else {
					$img = rand(0, 4);
					?>
					<?= Html::img('/img/avatar.jpg') ?>
				<?php } ?>
			</div>
			<div class="info">
				<h4><?= $athlete->getFullName() ?></h4>
				<?= $athlete->city->title ?>
				<h5>мотоциклы:</h5>
				<?php $motorcycles = $athlete->getMotorcycles()->andWhere(['status' => \common\models\Motorcycle::STATUS_ACTIVE])->all();
				if (count($motorcycles) == 1) {
					$motorcycle = reset($motorcycles);
					
					$html = $motorcycle->getFullTitle();
				} else {
					$html = '<ul>';
					foreach ($motorcycles as $motorcycle) {
						$html .= '<li>' . $motorcycle->getFullTitle() . '</li>';
					}
					$html .= '</ul>';
				}
				?>
				<?= $html ?>
				<div class="number">
					<?= $athlete->number ? '№' . $athlete->number : '' ?>
				</div>
			</div>
            <div class="triangle"><?= $athlete->athleteClass ? $athlete->athleteClass->title : '' ?></div>
		</div>
	</a>
</div>
