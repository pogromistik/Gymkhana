<?php
/**
 * @var \yii\web\View $this
 * @var array         $items
 */
$this->title = 'Московская схема для подсчёта баллов';
$cssClass = 'default';
?>

<h3><?= $this->title ?></h3>
<div class="row">
    <div class="col-sm-12 col-md-8">
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
</div>
* - каждый последующий участник, вошедший в данный класс получает такое же количество очков.