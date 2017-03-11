<?php
use yii\bootstrap\Html;

/**
 * @var \common\models\Stage[]       $newStages
 * @var \common\models\Participant[] $participants
 */
?>

    <h3>Ваши актуальные регистрации:</h3>
<?php if (!$participants) { ?>
    Вы не зарегистрированы ни на один из предстоящих этапов.
<?php } else { ?>
    <table class="table">
        <thead>
        <tr>
            <th>Мотоцикл</th>
            <th>Чемпионат</th>
            <th>Этап</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($participants as $participant) { ?>
            <tr>
                <td><?= $participant->motorcycle->getFullTitle() ?></td>
                <td><?= $participant->championship->title ?></td>
                <td><?= $participant->stage->title ?></td>
                <td>
					<?php if ($participant->status == \common\models\Participant::STATUS_ACTIVE) { ?>
						<?= Html::a('Отменить заявку', ['/profile/change-participant-status', 'id' => $participant->id],
							[
								'class'       => 'btn btn-light getRequest',
								'data-id'     => $participant->id,
								'data-action' => '/profile/change-participant-status'
							]) ?>
					<?php } else { ?>
						<?= Html::a('Возобновить заявку', ['/profile/change-participant-status', 'id' => $participant->id],
							[
								'class'       => 'btn btn-dark getRequest',
								'data-id'     => $participant->id,
								'data-action' => '/profile/change-participant-status'
							]) ?>
					<?php } ?>
                </td>
            </tr>
		<?php } ?>
        </tbody>
    </table>
<?php } ?>

<?php if ($newStages) { ?>
    <h3>Открыта регистрация на этапы: </h3>
	<?php if ($newStages) { ?>
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
	<?php } else { ?>
        В данный момент нет открытых регистраций.
	<?php } ?>
<?php } ?>