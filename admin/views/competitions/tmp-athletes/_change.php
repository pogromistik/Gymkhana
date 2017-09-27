<?php
use yii\bootstrap\Html;

/**
 * @var array                     $notFoundMotorcycles
 * @var \common\models\TmpAthlete $tmpAthlete
 * @var \common\models\Athlete    $oldAthlete
 */
?>

<div class="modal fade" id="changeMotorcycles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center"><h3>Выберите мотоциклы, которые необходимо добавить спортсмену</h3>
            </div>
            <div class="modal-body">
				<?php echo Html::beginForm(['/competitions/tmp-athletes/add-motorcycles-and-registration'], 'post', [
					'class' => 'addMotorcycleAndRegistration'
				]); ?>
				<div class="row">
                    <div class="col-md-6 col-sm-12">
                        <h4>Мотоциклы для добавления</h4>
	                    <?= Html::hiddenInput('tmpId', $tmpAthlete->id) ?>
	                    <?= Html::hiddenInput('athleteId', $oldAthlete->id) ?>
	                    <?php foreach ($notFoundMotorcycles as $i => $item) { ?>
                            <label>
			                    <?= Html::checkbox('motorcycles[' . $i . ']', false, ['value' => $i]) ?>
			                    <?= $item ?>
                            </label>
                            <br>
	                    <?php } ?>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h4>Мотоциклы, которые уже есть у спортсмена</h4>
                        <ul>
                        <?php foreach ($oldAthlete->motorcycles as $motorcycle) { ?>
                            <li><?= $motorcycle->getFullTitle() ?></li>
                        <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="pt-10">
                    <div class="alert alert-danger" style="display: none"></div>
                    <div class="button">
						<?= Html::submitButton(\Yii::t('app', 'Добавить выбранные мотоциклы и создать кабинет'),
                            ['class' => 'btn btn-my-style btn-green btn-block']) ?>
                    </div>
                    <div class="wait-text"></div>
                </div>
				<?php
				echo Html::endForm(); ?>
            </div>
        </div>
    </div>
</div>