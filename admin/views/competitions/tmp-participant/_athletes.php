<?php
use yii\bootstrap\Html;
/**
 * @var \common\models\Athlete[] $athletes
 * @var string                   $lastName
 */
?>

<div class="modal fade" id="athletesList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
				<?php if (!$athletes) { ?>
                    Спортсменов с фамилией "<?= $lastName ?>" не найдено
				<?php } else { ?>
                    <b>Для фамилии "<?= $lastName ?>" найдены следующие совпадения:</b>
                    <ul>
						<?php foreach ($athletes as $athlete) { ?>
                            <li><?= Html::a($athlete->getFullName() . ', ' . $athlete->city->title,
                                    ['/competitions/athlete/view', 'id' => $athlete->id],
                                    ['target' => '_blank']) ?></li>
						<?php } ?>
                    </ul>
				<?php } ?>
            </div>
        </div>
    </div>
</div>