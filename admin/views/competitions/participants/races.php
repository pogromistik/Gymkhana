<?php
use yii\bootstrap\ActiveForm;
use kartik\widgets\TimePicker;
use yii\widgets\MaskedInput;
use yii\bootstrap\Html;
use common\models\Championship;

/**
 * @var \yii\web\View                $this
 * @var \common\models\Stage         $stage
 * @var \common\models\Participant   $participant
 * @var string                       $error
 * @var \common\models\Participant[] $participants
 */
$championship = $stage->championship;
$this->title = $championship->title . ', ' . $stage->title;
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$championship->groupId], 'url' => ['/competitions/championships/index', 'groupId' => $championship->groupId]];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/championships/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['/competitions/stages/view', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = 'Заезды';
$attempt = 0;
?>

    <div class="alert alert-info">
        <div class="text-right">
            <span class="fa fa-remove closeHintBtn"></span>
        </div>
        Формат времени: минуты:секунды.миллисекунды. Необходимо обязательно указывать все 6 цифр.<br>
        В случае незачета всё равно укажите время, за которое человек проехал трассу. Если времени нет - оставьте поле
        пустым или введите
        время не меньше, чем 59:59.99.<br>
        Если штрафа нет - введите 0 или оставьте поле пустым.
    </div>

<?php if ($error) { ?>
    <div class="alert alert-danger">
        Не установлены классы спортсменов
		<?= Html::a('Установить', ['/competitions/stages/calculation-result', 'stageId' => $stage->id],
			[
				'class'   => 'btn btn-default setParticipantsClasses',
				'data-id' => $stage->id
			]) ?>
    </div>
<?php } ?>

<?php if (!$participants) { ?>
    <div class="alert alert-danger">
        Нет ни одного участника. Возможно, Вы забывали отметить пункт "участник приехал на этап" на странице со
		<?= Html::a('списком участников', ['/competitions/participants/index', 'stageId' => $stage->id]) ?>
    </div>
<?php } ?>

<?php while ($attempt++ < $stage->countRace) { ?>
    <h3>Заезд №<?= $attempt ?></h3>
    <div class="row">
        <div class="col-sm-1"><b>№ участника</b></div>
        <div class="col-sm-3"><b>ФИО</b></div>
        <div class="col-sm-3"><b>Мотоцикл</b></div>
        <div class="col-sm-2"><b>Время</b></div>
        <div class="col-sm-1"><b>Штраф</b></div>
        <div class="col-sm-1"><b>Незачет</b></div>
        <div class="col-sm-1"></div>
    </div>
    <hr>
	<?php
	foreach ($participants as $participant) {
		$timeModel = $participant->getTimeForm($attempt);
		?>
		<?php $form = ActiveForm::begin([
			'id'      => 'raceTimeForm' . $participant->id . '-' . $attempt,
			'options' => [
				'class' => 'raceTimeForm form-' . $attempt,
			]
		
		]); ?>
        <div class="row">
			<?= $form->field($timeModel, 'stageId')->hiddenInput()->label(false)->error(false) ?>
			<?= $form->field($timeModel, 'participantId')->hiddenInput()->label(false)->error(false) ?>
			<?= $form->field($timeModel, 'id')->hiddenInput(['class' => 'timeId'])->label(false)->error(false) ?>
			<?= $form->field($timeModel, 'attemptNumber')->hiddenInput(['value' => $attempt])->label(false)->error(false) ?>
            <div class="col-sm-1"><?= $participant->number ?></div>
            <div class="col-sm-3"><?= $participant->athlete->getFullName() ?></div>
            <div class="col-sm-3"><?= $participant->motorcycle->getFullTitle() ?></div>
            <div class="col-sm-2">
				<?= $form->field($timeModel, 'timeForHuman')->widget(MaskedInput::classname(), [
					'mask'    => '99:99.99',
					'options' => [
						'id'    => 'timeForHuman' . $participant->id . '-' . $attempt,
						'class' => 'form-control',
						'type'  => 'tel'
					]
				])->label(false) ?>
            </div>
            <div class="col-sm-1"><?= $form->field($timeModel, 'fine')->textInput()->label(false) ?></div>
            <div class="col-sm-1"><?= $form->field($timeModel, 'isFail')->checkbox(['id' => 'isFail-'.$timeModel->id.'-'.$timeModel->participantId.'-'.$attempt]) ?></div>
            <div class="col-sm-1">
                <button type="submit" class="btn btn-my-style <?= $timeModel->isNewRecord ?
                    'btn-green' : 'btn-blue' ?> btn-circle fa fa-save" title="Сохранить"></button>
            </div>
        </div>
		<?php $form->end(); ?>
		<?php
	} ?>
    <div class="row">
        <div class="col-sm-1 col-sm-offset-10">
            <a href="#" data-attempt="<?= $attempt ?>" data-count="<?= count($participants) ?>"
               class="saveAllStageResult btn btn-default">Сохранить всё</a>
        </div>
    </div>
<?php } ?>