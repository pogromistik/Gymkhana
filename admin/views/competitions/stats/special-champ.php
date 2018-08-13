<?php
/**
 * @var \yii\web\View $this
 * @var array         $byClasses
 * @var array         $byCities
 * @var array         $byCountries
 * @var array         $byStatus
 * @var integer       $unique
 * @var integer       $countAll
 */

$this->title = 'Статистика по Gymkhana GP 2018';
?>

<div>
    <h4>Количество участников: <?= $unique ?></h4>
</div>

<div>
    <table class="table">
        <tr>
            <td>Обработано заявок:</td>
            <td><?= $countAll ?></td>
        </tr>
        <tr>
            <td>Из них уникальных:
                <br>
                <small>(т.е. если взять только лучшие результаты спортсменов)</small>
            </td>
            <td><?= $byStatus[\common\models\RequestForSpecialStage::STATUS_APPROVE]["count"] ?></td>
        </tr>
        <tr>
            <td>Отклонено из-за несоответствия требованиям:</td>
            <td><?= $byStatus[\common\models\RequestForSpecialStage::STATUS_CANCEL]["count"] ?></td>
        </tr>
    </table>
</div>

<hr>

<h4>
    Повышение классов с момента первой отправки результата до завершения сезона<br>
    <small>Повышение класса не обязательно происходило по этому чемпионату. Подсчёт производился следующим образом:<br>
        Берём спортсмена, принявшего участие в GGP. Старый класс - это тот класс, в котором райдер был на момент
        отправки первого результата.
        Новый клсс - тот, в котором райдер находится в данный момент.
    </small>
</h4>
<table class="table">
    <tr>
        <th>Старый класс</th>
        <th>Новый класс</th>
        <th>Количество спортсменов</th>
    </tr>
	<?php foreach ($byClasses as $oldClassData) {
		if (!empty($oldClassData["classTitle"])) { ?>
            <tr>
                <td rowspan="<?= count($oldClassData["newClass"]) ?>"><?= $oldClassData["classTitle"] ?></td>
				<?php $first = array_shift($oldClassData["newClass"]); ?>
                <td><?= $first["title"] ?></td>
                <td><?= $first["count"] ?></td>
            </tr>
			<?php foreach ($oldClassData["newClass"] as $newClassData) { ?>
                <tr>
                    <td><?= $newClassData["title"] ?></td>
                    <td><?= $newClassData["count"] ?></td>
                </tr>
			<?php } ?>
		
		<?php }
	} ?>
</table>

<hr>

<h4>По странам</h4>
<table class="table">
	<?php foreach ($byCountries as $data) { ?>
        <tr>
            <td><?= $data["title"] ?></td>
            <td><?= $data["count"] ?></td>
        </tr>
	<?php } ?>
</table>

<hr>

<h4>По городам России</h4>
<table class="table">
	<?php foreach ($byCities as $data) { ?>
        <tr>
            <td><?= $data["title"] ?></td>
            <td><?= $data["count"] ?></td>
        </tr>
	<?php } ?>
</table>
