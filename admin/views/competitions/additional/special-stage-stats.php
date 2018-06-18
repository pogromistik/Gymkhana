<?php
/**
 * @var \common\models\SpecialStage             $stage
 * @var array                                   $stats
 * @var \common\models\RequestForSpecialStage[] $cancelRequests
 */
$countActive = 0;
$countInActive = 0;
?>

<?= \yii\helpers\Html::beginForm(['special-stage-stats', $stage ? $stage->id : 'null'], 'get') ?>
<?= \yii\helpers\Html::dropDownList('id', $stage ? $stage->id : 'null',
	\yii\helpers\ArrayHelper::map(\common\models\SpecialStage::find()->orderBy(['dateResult' => SORT_ASC])->all(), 'id', 'title'),
	['prompt' => 'Выберите этап...', 'onchange' => 'this.form.submit()', 'class' => 'form-control']) ?>
    <button type="submit" style="visibility: hidden;" title="Сохранить"></button>
<?= \yii\helpers\Html::endForm() ?>

<?php if (isset($stats[\common\models\RequestForSpecialStage::STATUS_NEED_CHECK])) { ?>
    <div>
        <b>Ожидает проверки:</b> <?= $stats[\common\models\RequestForSpecialStage::STATUS_NEED_CHECK]['count'] ?>
    </div>
<?php } ?>

<?php if (isset($stats[\common\models\RequestForSpecialStage::STATUS_IN_ACTIVE])) {
	$countInActive += $stats[\common\models\RequestForSpecialStage::STATUS_IN_ACTIVE]['count'];
}
if (isset($stats[\common\models\RequestForSpecialStage::STATUS_APPROVE])) {
	$countActive += $stats[\common\models\RequestForSpecialStage::STATUS_APPROVE]['count'];
}
?>
    <div>
        <b>Подтверждено результатов:</b> <?= $countActive + $countInActive ?>, из них <?= $countActive ?> актуальных
        и <?= $countInActive ?> были улучшены.
    </div>
<?php if (isset($stats[\common\models\RequestForSpecialStage::STATUS_CANCEL])) { ?>
    <div><b>Отклонено:</b> <?= $stats[\common\models\RequestForSpecialStage::STATUS_CANCEL]['count'] ?></div>
<?php } ?>

<?php if ($cancelRequests) { ?>
    <h4>Отклонённые результаты:</h4>
    <table class="table">
        <thead>
        <tr>
            <td>Город</td>
            <td>Спортсмен</td>
            <td>Результат</td>
            <td>Причина</td>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($cancelRequests as $request) {
			if ($athlete = $request->athlete) { ?>
                <tr>
                    <td><?= $athlete->city->title ?></td>
                    <td><?= $athlete->getFullName() ?></td>
                    <td><?= \yii\helpers\Html::a($request->resultTimeHuman, $request->videoLink, ['target' => '_blank']) ?></td>
                    <td><?= $request->cancelReason ?></td>
                </tr>
			<?php }
		} ?>
        </tbody>
    </table>
<?php } ?>