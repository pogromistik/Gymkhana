<?php
use yii\bootstrap\Html;

/**
 * @var \common\models\Stage[]       $newStages
 * @var \common\models\Participant[] $participants
 */
?>

    <h2>Ваши актуальные регистрации:</h2>
<?php if (!$participants) { ?>
    Вы не зарегистрированы ни на один из предстоящих этапов.
<?php } else { ?>
    <table class="table">
        <thead>
        <tr>
            <th>
                <div class="row">
                    <div class="col-md-9 col-xs-6">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">Мотоцикл</div>
                            <div class="col-md-4 col-sm-12">Чемпионат</div>
                            <div class="col-md-4 col-sm-12">Этап</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-6"></div>
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($participants as $participant) { ?>
            <tr>
                <td>
                    <div class="row">
                        <div class="col-md-9 col-xs-6">
                            <div class="row">
                                <div class="col-md-4 col-sm-12"><?= $participant->motorcycle->getFullTitle() ?></div>
                                <div class="col-md-4 col-sm-12"><?= $participant->championship->title ?></div>
                                <div class="col-md-4 col-sm-12"><?= $participant->stage->title ?></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6">
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
                        </div>
                    </div>
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
                    <td>
                        <div class="row">
                            <div class="col-md-9 col-xs-6">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
		                                <?= $newStage->championship->title ?>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
		                                <?= $newStage->title ?>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
		                                <?= $newStage->city->title ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-6">
	                            <?= Html::a('Подробнее', ['/competitions/stage', 'id' => $newStage->id]) ?>
                            </div>
                        </div>
                    </td>
                </tr>
			<?php } ?>
        </table>
	<?php } else { ?>
        В данный момент нет открытых регистраций.
	<?php } ?>
<?php } ?>