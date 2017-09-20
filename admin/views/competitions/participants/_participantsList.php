<?php
use yii\helpers\Html;

/**
 * @var array                $participants
 * @var \common\models\Stage $stage
 */
?>

<div class="modal fade" id="participantsList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="text-center">Следующие участники будут зарегистрированы на этап (<?= count($participants) ?>):</h4>
                <div class="btn-info-light small">
                    Если участник уже зарегистрирован на этап на выбранном мотоцикле, повторно заявка добавлена не
                    будет, т.е. снимать галочку не обязательно.
                </div>
				<?= Html::beginForm('#', 'post', ['id' => 'importParticipants']) ?>
				<?= Html::hiddenInput('stageId', $stage->id) ?>
				<?= Html::checkboxList('participants', array_keys($participants), $participants) ?>
                <div class="text-right">
                    <div class="wait" style="display: none">Пожалуйста, подождите...</div>
					<?= Html::submitButton('Импортировать', ['class' => 'btn btn-success']) ?>
                </div>
				<?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>
