<?php
use yii\helpers\Html;

/**
 * @var \common\models\FigureTime[] $results
 * @var \common\models\Figure       $figure
 * @var \common\models\Athlete      $athlete
 */
?>

<h3><?= Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>: <?= \Yii::t('app',
        'прогресс по фигуре "{figureTitle}"', [
                'figureTitle' => Html::a($figure->title, ['/competitions/figure', 'id' => $figure->id])
        ]) ?>
    </h3>

<?php if (!$results) { ?>
    <div><?= \Yii::t('app', 'У спортсмена нет ни одного результата по выбранной фигуре') ?></div>
<?php } else { ?>
    <div class="table-responsive">
        <table class="table results">
            <thead>
            <tr>
                <th><p><?= \Yii::t('app', 'Дата') ?></p></th>
                <th><p><?= \Yii::t('app', 'Мотоцикл') ?></p></th>
                <th><p><?= \Yii::t('app', 'Время') ?></p></th>
                <th><p><?= \Yii::t('app', 'Рейтинг') ?></p></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($results as $result) { ?>
                <tr>
                    <td><?= $result->dateForHuman ?></td>
                    <td><?= $result->motorcycle->getFullTitle() ?></td>
                    <td><?= $result->resultTimeForHuman ?></td>
                    <td><?= $result->actualPercent ?: $result->percent ?>%</td>
                </tr>
			<?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>
