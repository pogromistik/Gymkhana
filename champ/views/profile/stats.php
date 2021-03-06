<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View                 $this
 * @var array                         $figuresResult
 * @var \common\models\FigureTime     $result
 * @var \common\models\Figure         $figure
 * @var \common\models\ClassHistory[] $history
 * @var \common\models\Athlete        $athlete
 */
?>

<h2><?= \Yii::t('app', 'Статистика') ?></h2>

<div class="compareWithBlock">
	<?= $this->render('compareWith') ?>
    <hr>
</div>

<div class="figures pt-10 card-box">
    <h4>
        <?= \Yii::t('app', 'Результаты базовых фигур') ?><br>
        <small>
            <?= \Yii::t('app', 'Представлены только лучшие результаты. Для просмотра истории по конкретной фигуре, нажмите на её название') ?>
        </small>
    </h4>
	<?php if (!$figuresResult) { ?>
        <?= \Yii::t('app', 'Информация отсутствует') ?>
	<?php } else { ?>
        <table class="table table-bordered">
			<?php foreach ($figuresResult as $data) { ?>
                <tr>
					<?php
					$figure = $data['figure'];
					$result = $data['result'];
					?>
                    <td><?= $result->dateForHuman ?></td>
                    <td><?= Html::a($figure->title, ['/profile/stats-by-figure', 'figureId' => $figure->id]) ?></td>
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
                            <span class="green small">
                                <?= \Yii::t('app', 'лучше, чем {percent}% участников',
                                ['percent' => $data['percent']]) ?></span>
						<?php } ?>
                    </td>
                </tr>
			<?php } ?>
        </table>
	<?php } ?>
</div>

<?php if ($history) { ?>
    <div class="history pt-10 card-box">
        <h4>
            <?= \Yii::t('app', 'История переходов между классами') ?><br>
            <small><?= \Yii::t('app', 'показано не более 30 последних записей') ?></small>
        </h4>
        <table class="table">
            <tr>
                <th><?= \Yii::t('app', 'Дата') ?></th>
                <th><?= \Yii::t('app', 'Старый класс') ?></th>
                <th><?= \Yii::t('app', 'Новый класс') ?></th>
                <th><?= \Yii::t('app', 'Событие') ?></th>
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
