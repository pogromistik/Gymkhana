<?php
/**
 * @var \yii\web\View $this
 * @var array         $items
 */
$this->title = 'Московская схема для подсчёта баллов';
$cssClass = 'default';
?>

<div class="pb-10">
    Для начисления баллов берётся группа, в которой находится спортсмен после подсчёта итогов этапа.<br>
    * - каждый последующий участник, вошедший в данный класс получает такое же количество очков.
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <table class="table table-bordered">
            <tr class="dark-border">
                <th></th>
                <th>Место</th>
                <th>Баллы</th>
            </tr>
			<?php
			foreach ($items as $class => $places) {
				if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($class, 'UTF-8')])) {
					$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($class, 'UTF-8')];
				}
				$min = min($places);
				?>
                <tr>
                    <td colspan="3" class="text-center result-<?= $cssClass ?>"><?= $class ?></td>
                </tr>
				<?php
				$td = true;
				foreach ($places as $place => $point) { ?>
                    <tr class="<?= ($point == $min) ? 'dark-border' : null ?>">
						<?php if ($td) { ?>
                            <td rowspan="<?= count($places) ?>" class="result-<?= $cssClass ?>"></td>
							<?php $td = false;
						} ?>
                        <td><?= $place ?></td>
                        <td><?= $point ?>
							<?php if ($point == $min && $class != 'A') { ?>
                                *
							<?php } ?></td>
                    </tr>
				<?php } ?>
			<?php }
			?>
        </table>
    </div>
    <div class="col-sm-12 col-md-6 small">
        Пример: допустим, на этапе выступало 4 спортсмена из группы B и 2 спортсмена из группы C1. Из группы B никто не повысил свой класс -
        эти спортсмены получат 470, 460 и 455 баллов. Петя из группы C1 перешел в группу B, оказавшись четвёртым в этом списке. Он получает 455 баллов.
        Вася из группы C1 не повысил свой класс, он первый в группе C1 и получает 450 баллов.
    </div>
</div>