<?php
/**
 * @var \common\models\RequestForSpecialStage[] $requests
 * @var \common\models\Stage                    $stage
 * @var \common\models\Athlete                  $athlete
 */
?>

<h3><?= '"' . $stage->getTitle() . '": ' . $athlete->getFullName() ?></h3>

<div class="table-responsive card-box">
    <table class="table">
        <tbody>
		<?php foreach ($requests as $request) { ?>
            <tr>
                <td><?= $request->dateHuman ?></td>
                <td><?= $request->motorcycle->getFullTitle() ?></td>
                <td><?= $request->resultTimeHuman ?></td>
                <td><a href="<?= $request->videoLink ?>"><span class="fa fa-youtube"></span></a></td>
            </tr>
		<?php } ?>
        </tbody>
    </table>
</div>

<?= \yii\helpers\Html::a(\Yii::t('app', 'Вернуться к этапу'), ['/competitions/special-stage', 'id' => $stage->id], ['class' => 'btn btn-dark']) ?>