<?php
use common\models\Stage;
use yii\helpers\Html;

/**
 * @var \common\models\Stage        $model
 * @var \common\models\Championship $championship
 */
?>

<div class="alert help-alert alert-info">
    <div class="text-right">
        <span class="fa fa-remove closeHintBtn"></span>
    </div>
    <ol>
        <b>Описание работы с кнопками, поэтапно:</b>
        <li>
            До начала заездов вам придётся работать со страницей "Участники" - там производится регистрация
            участников; устанавливаются классы награждения, стартовые номера, порядок выступления спортсменов. В день
            соревнования <b>обязательно</b> нужно будет отметить на той странице пункт "участник приехал на этап", иначе
            спортсмен не отобразится в заездах.
        </li>
        <li>
            "Добавить время по фигурам" - квалификационные заезды для присвоения начальных классов (A-N)
            спортсменам. Их можно заполнять параллельно с регистрацией.
        </li>
        <li>
            После того, как регистрация была завершена, необходимо "сформировать итоговые списки". При этом действии все
            заявки, у которых не отмечен пункт "участник приехал на этап", уйдут в "отменённые". Если вы забудете
            совершить это действие - ничего страшного, просто неприехавшие спортсмены продолжат отображаться на всех
            страницах основного сайта.
        </li>
        <li>
            Перед началом заездов (когда завершена регистрация и прошли квалификационные заезды), необходимо установить
            класс соревнования. Если вы установили класс, а потом зарегистрировали ещё кого-то - желательно пересчитать
            класс заново.
            Если вы забыли нажать эту кнопку - не переживайте, это можно сделать в любой момент, даже после заездов.
            Посчитать итоги с  неустановленным классом система не даст.
        </li>
        <li>
            Далее работа начинается со страницей "Заезды" - именно там записываются результаты проезда трассы.
        </li>
        <li>
            После завершения заездов (ВСЕХ попыток всех участников) необходимо нажать кнопку "пересчитать результаты" -
            будут рассчитаны места и новые классы для спортсменов. Если после нажатия на кнопку вы понимаете, что
            опечатались в каких-то результатах - исправьте их в "заездах" и заново пересчитайте.
        </li>
        <?php if ($championship->useMoscowPoints) { ?>
            <li>
                После подсчёта итогов вы можете начислить баллы за этот этап.
            </li>
        <?php } ?>
        <li>
            На странице "Итоги" выводится итоговая таблица с результатами этого этапа. Там же можно добавить видео заездов.
        </li>
        <li>
            Будьте внимательны - при совершении большинства действий система показывает сообщения. Читайте их - там
            может быть как текст о успехе операции, так и о каких-либо ошибках.
        </li>
    </ol>
</div>

<div class="row with-hr-border">
    <div class="col-md-6">
        <h3>Подготовка к этапу</h3>
		
		<?= Html::a('Участники', ['/competitions/participants/index', 'stageId' => $model->id],
			['class' => 'btn btn-my-style btn-light-aquamarine']) ?>
		<?php if ($model->status != Stage::STATUS_CALCULATE_RESULTS && $model->status != Stage::STATUS_PAST) { ?>
			<?= Html::a('Добавить время по фигурам',
				['/competitions/stages/add-figures-results', 'stageId' => $model->id], ['class' => 'btn btn-my-style btn-light-blue']) ?>
		<?php } ?>
        <br>
		<?= Html::a('Сформировать итоговые списки', ['/competitions/participants/set-final-list', 'stageId' => $model->id],
			[
				'class'   => 'btn btn-my-style btn-burgundy setFinalList',
				'data-id' => $model->id
			]) ?>
		<?= Html::a('Установить класс соревнования', ['/competitions/participants/set-classes', 'stageId' => $model->id],
			[
				'class'   => 'btn btn-my-style btn-gray setParticipantsClasses',
				'data-id' => $model->id
			]) ?>
    </div>

    <div class="col-md-6">
        <h3>Проведение этапа</h3>
		
		<?= Html::a('Заезды', ['/competitions/participants/races', 'stageId' => $model->id],
			['class' => 'btn btn-my-style btn-aquamarine']) ?>
		
		<?= Html::a('Пересчитать результаты', ['/competitions/stages/calculation-result', 'stageId' => $model->id],
			[
				'class'   => 'btn btn-my-style btn-yellow stageCalcResult',
				'data-id' => $model->id
			]) ?>
		<?php if ($championship->useMoscowPoints) { ?><?= Html::a('Начислить баллы', ['/competitions/stages/accrue-points', 'stageId' => $model->id],
			[
				'class'   => 'btn btn-my-style btn-peach accruePoints',
				'data-id' => $model->id
			]) ?>
		<?php } ?>
		<?= Html::a('Итоги', ['/competitions/stages/result', 'stageId' => $model->id],
			['class' => 'btn btn-my-style btn-lilac']) ?>
    </div>
</div>
<?php if (\Yii::$app->user->can('developer')) { ?>
    <div class="pt-10">
        <?= Html::a('Логи', ['/competitions/developer/logs', 'modelClass' => Stage::class,
            'modelId' => $model->id], ['class' => 'dev-logs dev-logs-btn']) ?>
    </div>
<?php } ?>

