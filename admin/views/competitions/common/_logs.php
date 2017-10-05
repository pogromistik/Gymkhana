<h3>Логи для записи <?= $model->id ?> из таблицы <?= $model::className() ?></h3>
<?php
$changes = $model->getChanges();
if ($changes) {
	?>
    <div class="pt-10">
        <div class="row table">
            <div class="col-md-2"><b>Дата</b></div>
            <div class="col-md-1"><b>User</b></div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4"><b>Изменения</b></div>
                    <div class="col-md-4"><b>Старое</b></div>
                    <div class="col-md-4"><b>Новое</b></div>
                </div>
            </div>
        </div>
    </div>
	<?php
	$index = 0;
	foreach ($changes as $change) {
		$index++;
		$class = $index % 2 ? 'odd' : 'even';
		$list = $change->getChanges();
		$attrs = array_keys($list);
		$rows = count($list) + 1;
		?>
        <div class="changes <?= $class ?>">
            <div class="row table">
                <div class="col-md-2"><?= date('d.m.Y H:i:s', $change->date) ?></div>
                <div class="col-md-1"><?= $change->user ? $change->user->username : '-' ?></div>
                <div class="col-md-8">
					<?php
					foreach ($list as $attr => $data) {
						$label = $model->getAttributeLabel($attr);
						?>
                        <div class="row pb-1">
                            <div class="col-md-4">
								<?= $label ?>
                            </div>
                            <div class="col-md-4 old">
								<?= $model->getAttributeDisplayValue($attr, $data['old']) ?>
                            </div>
                            <div class="col-md-4 new">
								<?= $model->getAttributeDisplayValue($attr, $data['new']) ?>
                            </div>
                        </div>
					<?php } ?>
                </div>
            </div>
            <hr>
        </div>
		<?php
	}
	?>
	<?php
}
?>