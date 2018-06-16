<?php
/**
 * @var \yii\web\View                         $this
 * @var array                                 $result
 * @var \common\models\RequestForSpecialStage $request
 */

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;

$this->title = 'Регистрации на особые этапы, требующие модерации';
?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>Чемпионат</th>
        <th>Заявка</th>
        <th>Совпадения</th>
        <th></th>
    </tr>
    </thead>

    <tbody>
	<?php foreach ($result as $i => $item) {
		$request = $item['request'];
		$coincidences = $item['coincidences'];
		?>
        <tr>
            <td>
				<?= $request->stage->championship->title ?>
                <br>
                <small><?= $request->stage->title ?></small>
                <br>
                Прислано: <?= date("d.m.Y, H:i", $request->dateAdded) ?>
            </td>
            <td>
				<?php if ($request->athleteId) { ?>
					<?= $request->athlete->getFullName() ?>
                    <br>
                    <small><?= $request->city->title ?></small>
                    <br>
                    <small><?= $request->motorcycle->getFullTitle() ?></small>
				<?php } else { ?>
					<?php $data = $request->getData(); ?>
					<?= $data['lastName'] . ' ' . $data['firstName'] ?>
                    <br>
                    <small><?= $request->cityId ? $request->city->title . '(' . $request->country->title . ')' : $data['cityTitle'] ?></small>
                    <br>
                    <div>
                        <div id="tmp-motorcycle-<?= $request->id ?>-1" class="small">
							<?= $data['motorcycleMark'] . ' ' . $data['motorcycleModel'] ?>,
							<?= $data['cbm'] ?>м<sup>3</sup>, <?= $data['power'] ?>л.с.
                            <br>
							<?php if (isset($data['isCruiser']) && $data['isCruiser'] == 1) { ?>
                                <b>круизёр</b>
							<?php } else { ?>
                                <b>не круизёр</b>
							<?php } ?>
                        </div>
                        <a href="#" data-id="<?= $request->id ?>" data-motorcycle-id="<?= 1 ?>" data-mode="stage"
                           class="changeTmpMotorcycle">изменить</a>
                    </div>
					
					<?php if (!$request->cityId) { ?>
                        <b>Город не привязан к списку. Отредактируйте заявку.</b>
					<?php } ?>
				<?php } ?>
                <div>
                    <a href="#" data-id="<?= $request->id ?>"
                       class="btn btn-default btn-xs change-special-request">Редактировать заявку</a>
                    <div id="changeTmpRequest<?= $request->id ?>"></div>
                </div>
                <div class="dark-green-text bold">
                    <div>Дата: <?= $request->dateHuman ?></div>
                    <div>Время: <?= $request->timeHuman ?></div>
                    <div>Штраф: <?= $request->fine ?></div>
                    <div>Итого: <?= $request->resultTimeHuman ?></div>
                    <div><a href="<?= $request->videoLink ?>" target="_blank">посмотреть видео</a></div>
                </div>
            </td>
            <td>
				<?php if ($coincidences) { ?>
					<?php foreach ($coincidences as $coincidence) {
						/** @var \common\models\Athlete $athlete */
						$athlete = $coincidence['athlete']; ?>
						<?= $athlete->getFullName() . ', ' . $athlete->city->title ?>
                        <a href="#" class="btn btn-my-style small btn-dirty-blue approveSpecChampForAthlete"
                           data-athlete-id="<?= $athlete->id ?>" data-id="<?= $request->id ?>">
                            Принять на новом мотоцикле
                        </a>
                        <div>
							<?php foreach ($coincidence['motorcycles'] as $motorcycleData) {
								/** @var \common\models\Motorcycle $motorcycle */
								$motorcycle = $motorcycleData['motorcycle'];
								?>
                                <div>
									<?= $motorcycle->getFullTitle(); ?>
                                    <a href="#"
                                       class="btn btn-my-style small btn-light-gray approveSpecChampForAthleteOnMotorcycle"
                                       data-athlete-id="<?= $athlete->id ?>" data-id="<?= $request->id ?>"
                                       data-motorcycle-id="<?= $motorcycle->id ?>">
                                        Принять на этом мотоцикле
                                    </a>
                                </div>
							<?php } ?>
                        </div>
                        <a href="#" data-last-name="<?= $request->getData()['lastName'] ?>"
                           class="findByFirstName btn btn-my-style small btn-default">
                            список всех спортсменов с такой фамилией</a>
					<?php } ?>
				<?php } ?>
            </td>
            <td>
                <div>
                    <a href="#" class="btn btn-my-style btn-green small getRequest"
                       data-id="<?= $request->id ?>" data-action="/competitions/special-champ/approve">Принять
                        результат</a>
                </div>
                <div>
                    <a href="#" class="btn btn-my-style btn-red small cancelFigureResult"
                       data-id='<?= $request->id ?>'>
                        Отклонить результат
                    </a>
                </div>

            </td>
        </tr>
	<?php } ?>
    </tbody>
</table>


<div class="modalList"></div>

<div class="modal fade" id="cancelFigureResult" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<?= Html::beginForm('', 'post', [
				'id' => 'cancelRegForSpecStage'
			]) ?>
            <div class="modal-body">
                <h3>Укажите причину отказа</h3>
				<?= Html::hiddenInput('id', '', ['id' => 'id']) ?>
				<?= Html::textarea('reason', '', ['rows' => 3, 'class' => 'form-control']) ?>
            </div>
            <div class="alert alert-danger" style="display: none"></div>
            <div class="alert alert-success" style="display: none"></div>
            <div class="modal-footer">
                <div class="form-text"></div>
                <div class="button">
					<?= Html::submitButton('Отклонить результат', ['class' => 'btn btn-lg btn-block btn-my-style btn-green']) ?>
                </div>
            </div>
			<?= Html::endForm() ?>
        </div>
    </div>
</div>

<div class="modalChangeTmpMotorcycle"></div>