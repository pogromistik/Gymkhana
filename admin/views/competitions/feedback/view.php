<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Feedback */

$this->title = 'Сообщение от ' . date("d.m.Y, H:i", $model->dateAdded);
$this->params['breadcrumbs'][] = ['label' => 'Обратная связь', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-view">

    <table class="table table-striped table-bordered detail-view">
        <tbody>
        <tr>
            <th>Имя</th>
            <td>
                <?php if ($model->athleteId) { ?>
                    <?= Html::a($model->username, ['/competitions/athlete/view', 'id' => $model->athleteId], [
                            'target' => 'blank'
                    ]) ?>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th>Телефон</th>
            <td>
                <?php if (!$model->phone && $model->athleteId) { ?>
                    <?= $model->athlete->phone ?>
                <?php } else { ?>
                    <?= $model->phone ?>
                <?php } ?>
        </tr>
        <tr>
            <th>Email</th>
            <td>
	            <?php if (!$model->email && $model->email) { ?>
		            <?= $model->athlete->email ?>
	            <?php } else { ?>
		            <?= $model->email ?>
	            <?php } ?>
            </td>
        </tr>
        <tr>
            <th>Текст</th>
            <td><?= $model->text ?></td>
        </tr>
        </tbody>
    </table>
    
    <?= Html::a('Отметить как новое', ['/competitions/feedback/change-status', 'id' => $model->id],
        ['class' => 'btn btn-success']) ?>
</div>
