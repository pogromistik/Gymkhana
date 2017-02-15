<?php
use yii\bootstrap\Html;

/**
 * @var \common\models\Stage[] $newStages
 */
?>

<?php if ($newStages) { ?>
    <h3>Открыта регистрация на этапы: </h3>
    <table class="table table-striped">
		<?php foreach ($newStages as $newStage) { ?>
            <tr>
                <td><?= $newStage->championship->title ?></td>
                <td><?= $newStage->title ?></td>
                <td><?= $newStage->city->title ?></td>
                <td><?= Html::a('Подробнее', ['/competitions/stage', 'id' => $newStage->id]) ?></td>
            </tr>
		<?php } ?>
    </table>
<?php } ?>