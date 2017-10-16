<?php
/**
 * @var \yii\web\View                           $this
 * @var \common\models\SpecialStage             $stage
 * @var \common\models\RequestForSpecialStage[] $requests
 */
$this->title = $stage->title;
$newClasses = $stage->getActiveRequests()->andWhere(['not', ['newAthleteClassId' => null]])
	->andWhere(['newAthleteClassStatus' => \common\models\RequestForSpecialStage::NEW_CLASS_STATUS_NEED_CHECK])->all();
$championship = $stage->championship;
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['/competitions/special-champ/index']];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/special-champ/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['/competitions/special-champ/view-stage', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="alert help-alert alert-info">
    <div class="text-right">
        <span class="fa fa-remove closeHintBtn"></span>
    </div>
    <ul>
        <li>
            Обратите внимание, что новые классы спортсменов требуют подтверждения. Если спортсмены повысят класс -
            появятся
            кнопки для его подтверждения и отклонения. Можно подтвердить\отклонить как все результаты сразу, так и один
            конкретный. Если какие-то результаты вызывают у вас подозрение - рекомендуем проверить их на предмет
            опечаток и при
            необходимости изменить время в "заездах", после чего заново "пересчитать результаты".
        </li>
        <li>
            Если вы подтвердили новый класс, а потом выяснилось, что в результате допущена опечатка и фактический класс
            спортсмена ниже - измените класс в профиле спортсмена или на
            странице "<?= \yii\helpers\Html::a('изменить класс спортсмена', ['/competitions/athlete/change-class']) ?>".
        </li>
    </ul>
</div>

<?php if ($stage->class) { ?><h4>Класс соревнования: <?= $stage->class->title ?></h4><?php } ?>
<?php if ($stage->referenceTime) { ?><h4>Эталонное время: <?= $stage->referenceTimeHuman ?></h4><?php } ?>

<?php if ($newClasses) { ?>
    <div class="text-right newClass">
        <div class="pb-10">
            <a class="btn btn-danger getRequest" href="#"
               data-action="/competitions/special-champ/cancel-all-classes"
               data-id="<?= $stage->id ?>" title="Отменить">
                Отменить все новые неподтверждённые классы
            </a>
            <a class="btn btn-success getRequest" href="#"
               data-action="/competitions/special-champ/approve-all-classes"
               data-id="<?= $stage->id ?>" title="Подтвердить">
                Подтвердить все новые классы
            </a>
        </div>
    </div>
<?php } ?>

<table class="table results">
    <thead>
    <tr>
        <th>Место</th>
        <th>Группа</th>
        <th>Участник</th>
        <th>Мотоцикл</th>
        <th>Время</th>
        <th>Штраф</th>
        <th>Итоговое время</th>
        <th>Рейтинг</th>
        <th>Новый класс</th>
        <th>Баллы за этап</th>
    </tr>
    </thead>
    <tbody>
	<?php foreach ($requests as $request) {
		$athlete = $request->athlete;
		?>
        <tr>
            <td><?= $request->place ?></td>
            <td><?= $athlete->athleteClass->title ?></td>
            <td><?= $athlete->getFullName() ?></td>
            <td><?= $request->motorcycle->getFullTitle() ?></td>
            <td><?= $request->timeHuman ?></td>
            <td><?= $request->fine ?></td>
            <td><?= $request->resultTimeHuman ?></td>
            <td><?= $request->percent ? $request->percent . '%' : '' ?></td>
            <td class="newClass">
	        <?php if ($request->newAthleteClassId) { ?>
		        <?= $request->newAthleteClass->title ?>
		        <?php if ($request->newAthleteClassStatus == \common\models\RequestForSpecialStage::NEW_CLASS_STATUS_NEED_CHECK) { ?>
                    <br>
                    <a class="btn btn-danger getRequest" href="#"
                       data-action="/competitions/special-champ/cancel-class"
                       data-id="<?= $request->id ?>" title="Отменить">
                        <span class="fa fa-remove"></span>
                    </a>
                    <a class="btn btn-success getRequest" href="#"
                       data-action="/competitions/special-champ/approve-class"
                       data-id="<?= $request->id ?>" title="Подтвердить">
                        <span class="fa fa-check"></span>
                    </a>
		        <?php } ?>
	        <?php } ?>
            </td>
            <td></td>
        </tr>
	<?php } ?>
    </tbody>
</table>

