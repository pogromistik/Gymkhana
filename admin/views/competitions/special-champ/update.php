<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SpecialChamp */

$this->title = 'Редактирование чемпионата: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="special-champ-update">
	
    <p>
	    <?= Html::a('Добавить этап', ['/competitions/special-champ/create-stage', 'championshipId' => $model->id], [
		    'class' => 'btn btn-my-style btn-light-green'
	    ]); ?>
	    <?= Html::a('Результаты', ['/competitions/special-champ/results', 'id' => $model->id], [
		    'class' => 'btn btn-my-style btn-lilac'
	    ]); ?>
    </p>
    
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

    <div class="stages">
        <h3>Этапы</h3>
        <table class="table">
            <thead>
            <tr>
                <th>Название</th>
                <th>Статус</th>
                <th>Класс соревнования</th>
                <th></th>
            </tr>
            </thead>
			<?php foreach ($model->stages as $stage) { ?>
                <tr>
                    <td><?= Html::a($stage->title, ['/competitions/special-champ/view-stage', 'id' => $stage->id]) ?></td>
                    <td><?= \common\models\Stage::$statusesTitle[$stage->status] ?></td>
                    <td><?= $stage->classId ? $stage->class->title : null ?></td>
                    <td>
						<?= Html::a('<span class="fa fa-user btn-my-style btn-light-aquamarine small"></span>',
							['/competitions/special-champ/participants', 'stageId' => $stage->id]) ?>
						<?php if (\Yii::$app->user->can('projectAdmin')) { ?>
                            &nbsp;
							<?= Html::a('<span class="fa fa-edit btn-my-style btn-blue small"></span>',
								['/competitions/special-champ/update-stage', 'id' => $stage->id]) ?>
						<?php } ?>
                    </td>
                </tr>
			<?php } ?>
        </table>
    </div>
</div>
