<?php
use yii\bootstrap\Html;

/**
 * @var \common\models\Athlete      $model
 * @var \common\models\Motorcycle[] $motorcycles
 */
$athlete = $model;
$cssClass = 'default';
$athleteClass = null;
if ($athlete->athleteClassId) {
	$athleteClass = $athlete->athleteClass;
	if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($athleteClass->title, 'UTF-8')])) {
		$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($athleteClass->title, 'UTF-8')];
	}
}
?>

<div class="col-xs-12 col-sm-6 col-lg-4 col-bg-3 item">
    <a href="/athletes/view?id=<?= $athlete->id ?>">
        <div class="card">
            <div class="img"
                 style="background-image: url(<?= $athlete->photo ?
		             \Yii::getAlias('@filesView') . $athlete->photo : '/img/avatar.jpg' ?>)">
            </div>
            <div class="info">
                <h4><?= $athlete->getFullName() ?></h4>
                <?= $athlete->country->getTitle() ?>,
				<?= \common\helpers\TranslitHelper::translitCity($athlete->city->title) ?>
                <h5><?= \Yii::t('app', 'мотоциклы:') ?></h5>
				<?php $motorcycles = $athlete->getMotorcycles()->andWhere(['status' => \common\models\Motorcycle::STATUS_ACTIVE])->limit(2)->all();
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
            <div class="triangle triangle-<?= $cssClass ?>"><?= $athleteClass ? $athleteClass->title : '' ?></div>
        </div>
    </a>
</div>
