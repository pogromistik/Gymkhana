<?php
use yii\helpers\Html;

/**
 * @var \common\models\FigureTime[] $results
 * @var \common\models\Figure       $figure
 * @var \common\models\Athlete      $athlete
 */
?>

<h3><?= Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>: прогресс по фигуре
    "<?= Html::a($figure->title, ['/competitions/figure', 'id' => $figure->id]) ?>"</h3>

<?php if (!$results) { ?>
    <div>У спортсмена нет ни одного результата по выбранной фигуре</div>
<?php } else { ?>
    <div class="table-responsive">
        <table class="table results">
            <thead>
            <tr>
                <th><p>Дата</p></th>
                <th><p>Мотоцикл</p></th>
                <th><p>Время</p></th>
                <th><p>Рейтинг</p></th>
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
