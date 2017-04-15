<?php
/**
 * @var \yii\web\View              $this
 * @var \common\models\CheScheme[] $items
 */
$this->title = 'Челябинская схема для награждения';
?>

Классы награждения получаются путём объединения классов спортсмена:

<ul>
	<?php
	$prev = null;
	foreach ($items as $item) { ?>
        <li>
            <b>Класс "<?= $item->title ?>":</b> <?= $item->description ?>,
			<?php if ($prev) { ?>
			(<?= $prev ?>% - <?= $item->percent == 1000 ? '...' :  $item->percent ?>% включительно)
			<?php } else { ?>
                (<?= $item->percent ?>% включительно)
			<?php } ?>
        </li>
		<?php
		$prev = $item->percent;
	} ?>
</ul>
