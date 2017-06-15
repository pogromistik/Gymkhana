<?php
/**
 * @var \yii\web\View                $this
 * @var \common\models\MoscowPoint[] $items
 */
$this->title = 'Московская схема для подсчёта баллов';
$cssClass = 'default';
?>

<div class="row">
    <div class="col-sm-12 col-md-6">
        <table class="table table-bordered">
            <tr>
                <th>Место</th>
                <th>Баллы</th>
            </tr>
            <tr>
				<?php
				$first = array_shift($items);
				$class = $first->class;
				$title = $first->classModel->title;
				if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($title, 'UTF-8')])) {
					$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($title, 'UTF-8')];
				}
				?>
            <tr>
                <td colspan="2" class="text-center result-<?= $cssClass ?>">
					<?= $title ?>
                </td>
            </tr>
            <tr>
                <td><?= $first->place ?></td>
                <td><?= $first->point ?></td>
            </tr>
			<?php
			foreach ($items as $item) { ?>
				<?php if ($item->class != $class) {
					$class = $item->class;
					$title = $item->classModel->title;
					if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($title, 'UTF-8')])) {
						$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($title, 'UTF-8')];
					}
					?>
                    <tr>
                        <td colspan="2" class="text-center result-<?= $cssClass ?>">
							<?= $title ?>
                        </td>
                    </tr>
				<?php } ?>
                <tr>
                    <td><?= $item->place ?></td>
                    <td><?= $item->point ?></td>
                </tr>
			<?php } ?>
            </tr>
        </table>
    </div>
</div>