<?php
use yii\bootstrap\ActiveForm;
/**
 * @var string                           $error
 * @var \admin\models\FigureTimeForStage $figureTime
 * @var \common\models\Athlete           $athlete
 */
?>

<?php if ($error) { ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php } else { ?>
    Спортсмену <?= $athlete->getFullName() ?> будет добавлено время <?= $figureTime->timeForHuman ?>
	<?php if ($figureTime->fine) { ?> +<?= $figureTime->fine ?><?php } ?>.<br>
    Процент отставания от лидера: <?= $figureTime->percent ?>%<br>
	<?php if ($figureTime->newClassId) { ?>
        Спортсмену будет установлен новый класс: <?= $figureTime->newClassTitle ?>.
	<?php } else { ?>
        Класс спортсмена не изменился
	<?php } ?>
    <?php if ($figureTime->newClassForParticipant) { ?>
        <br>
        Спортсмену для этапа будет установлен класс: <?= $figureTime->newClassTitle ?>.
        <?php } ?>
    
    <div class="form">
	    <?php $form = ActiveForm::begin(['id' => 'addFigureTimeForStage']); ?>
	
	    <?= $form->field($figureTime, 'date')->hiddenInput()->label(false)->error(false) ?>
	    <?= $form->field($figureTime, 'stageId')->hiddenInput()->label(false)->error(false) ?>
	    <?= $form->field($figureTime, 'figureId')->hiddenInput()->label(false)->error(false) ?>
	    <?= $form->field($figureTime, 'participantId')->hiddenInput()->label(false)->error(false) ?>
	    <?= $form->field($figureTime, 'motorcycleId')->hiddenInput()->label(false)->error(false) ?>
	    <?= $form->field($figureTime, 'timeForHuman')->hiddenInput()->label(false)->error(false) ?>
	    <?= $form->field($figureTime, 'fine')->hiddenInput()->label(false)->error(false) ?>
	    <?= $form->field($figureTime, 'percent')->hiddenInput()->label(false)->error(false) ?>
	    <?= $form->field($figureTime, 'newClassId')->hiddenInput()->label(false)->error(false) ?>
	    <?= $form->field($figureTime, 'resultTime')->hiddenInput()->label(false)->error(false) ?>
	    <?= $form->field($figureTime, 'newClassForParticipant')->hiddenInput()->label(false)->error(false) ?>
        
        <?= \yii\bootstrap\Html::submitButton('Нажмите чтобы добавить результат', ['class' => 'btn btn-primary']) ?>
        
	    <?php $form->end() ?>
    </div>
<?php } ?>
