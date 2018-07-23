<?php
/**
 * @var array $result
 * @var int   $yearId
 * @var int   $type
 */
?>
<?= \yii\bootstrap\Html::beginForm(['index'], 'get'); ?>
<div class="row">
    <div class="col-md-5">
		<?= \yii\helpers\Html::dropDownList('yearId', $yearId,
			\yii\helpers\ArrayHelper::map(\common\models\Year::find()->orderBy(['year' => SORT_DESC])->all(), 'id', 'year'),
			['onchange' => 'this.form.submit()', 'prompt' => 'Выберите год...', 'class' => 'form-control']) ?>
    </div>
    <div class="col-md-5">
		<?= \yii\helpers\Html::dropDownList('type', $type,
			[\champ\controllers\StatsController::TYPE_STAGE => 'Этапы', \champ\controllers\StatsController::TYPE_SPECIAL_STAGE => 'Gymkhana GP'],
			['onchange' => 'this.form.submit()', 'prompt' => 'Выберите данные...', 'class' => 'form-control']) ?>
    </div>
    <div class="col-md-2">
		<?= \yii\helpers\Html::a('Скачать', ['download', 'yearId' => $yearId, 'type' => $type], ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?= \yii\helpers\Html::endForm(); ?>

<?php if (empty($result)) { ?>
    Результатов не найдено
<?php } else { ?>
    <table class="table">
        <thead>
        <tr>
            <th>Id спортсмена</th>
            <th>ФИО</th>
            <th>Рейтинг</th>
            <th>Id этапа</th>
            <th>Этап</th>
            <th>Id чемпионата</th>
            <th>Чемпионат</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($result as $athleteId => $items) {
			foreach ($items as $item) {
				?>
                <tr>
                    <td><?= $item['athleteId'] ?></td>
                    <td><?= $item['athleteName'] ?></td>
                    <td><?= $item['percent'] ?></td>
                    <td><?= $item['stageId'] ?></td>
                    <td><?= $item['stageTitle'] ?></td>
                    <td><?= $item['champId'] ?></td>
                    <td><?= $item['champTitle'] ?></td>
                </tr>
				<?php
			}
		} ?>
        </tbody>
    </table>
<?php } ?>
