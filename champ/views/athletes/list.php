<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \common\models\Athlete[]            $athletes
 * @var \yii\web\View                       $this
 * @var \common\models\search\AthleteSearch $searchModel
 * @var \yii\data\ActiveDataProvider        $dataProvider
 */
?>

<div class="athletes">
    <div class="row">
		<?php foreach ($athletes as $athlete) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12 item">
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
                    <div class="athleteClass">
		                <?= $athlete->athleteClass ? $athlete->athleteClass->title : '' ?>
                    </div>
                </div>
                </a>
            </div>
		<?php } ?>
    </div>
</div>
