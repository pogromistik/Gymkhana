<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var array                         $figuresResult
 * @var \common\models\FigureTime     $result
 * @var \common\models\Figure         $figure
 * @var \common\models\ClassHistory[] $history
 * @var \common\models\Athlete $athlete
 */
?>

<h3>Статистика</h3>

<?= $this->render('compareWith') ?>

<div class="figures pt-10">
	<h4>
		Результаты базовых фигур<br>
		<small>представлены только лучшие результаты</small>
	</h4>
	<?php if (!$figuresResult) { ?>
		Информация отсутствует
	<?php } else { ?>
		<table class="table table-bordered">
			<?php foreach ($figuresResult as $data) { ?>
				<tr>
					<?php
					$figure = $data['figure'];
					$result = $data['result'];
					?>
					<td><?= $result->dateForHuman ?></td>
					<td><?= Html::a($figure->title, ['/competitions/figure', 'id' => $figure->id], ['target' => '_blank']) ?></td>
					<td>
						<?= $result->resultTimeForHuman ?>
						<?php if ($result->fine) { ?>
							<small> (<?= $result->timeForHuman ?> +<?= $result->fine ?>)</small>
						<?php } ?>
						<?php if ($result->recordType && $result->recordStatus == \common\models\FigureTime::NEW_RECORD_APPROVE) { ?>
							<?= \yii\bootstrap\Html::img('/img/crown.png', [
								'title' => \common\models\FigureTime::$recordsTitle[$result->recordType] . '!',
								'alt'   => \common\models\FigureTime::$recordsTitle[$result->recordType] . '!'
							]) ?>
						<?php } ?>
					</td>
					<td>
                        <?= $result->percent ?>%
                        <?php if ($data['percent'] >= 30) { ?>
                            <span class="green small">лучше, чем <?= $data['percent'] ?>% участников</span>
                        <?php } ?>
                    </td>
				</tr>
			<?php } ?>
		</table>
	<?php } ?>
</div>

<?php if ($history) { ?>
	<div class="history pt-10">
		<h4>
			История переходов между классами<br>
			<small>показано не более 30 последних записей</small>
		</h4>
		<table class="table">
			<tr>
				<th>Дата</th>
				<th>Старый класс</th>
				<th>Новый класс</th>
				<th>Событие</th>
			</tr>
			<?php foreach ($history as $item) { ?>
				<tr>
					<td><?= $item->dateForHuman ?></td>
					<td><?= $item->oldClassId ? $item->oldClass->title : '' ?></td>
					<td><?= $item->newClass->title ?></td>
					<td><?= $item->event ?></td>
				</tr>
			<?php } ?>
		</table>
	</div>
<?php } ?>
