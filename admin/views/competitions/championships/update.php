<?php

use common\models\Championship;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var Championship  $model
 * @var integer       $success
 */

$this->title = 'Редактировать чемпионат: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$model->groupId], 'url' => ['index', 'groupId' => $model->groupId]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';

$stages = $model->stages;
?>
<div class="championship-update">
	
	<?php if (!$stages) { ?>
        <div class="alert alert-danger">Даже если у вас одноэтапный чемпионат, необходимо всё равно создать этап.</div>
	<?php } ?>
	
	<?php if ($success) { ?>
        <div class="alert alert-success">
            Изменения успешно сохранены
        </div>
	<?php } ?>

    <div class="pb-20"><?= Html::a('Добавить этап', ['/competitions/stages/create', 'championshipId' => $model->id], [
			'class' => 'btn btn-my-style btn-light-green'
		]); ?>
		<?= Html::a('Результаты', ['/competitions/championships/results', 'championshipId' => $model->id], [
			'class' => 'btn btn-my-style btn-lilac'
		]); ?>
    </div>

    <div class="row with-hr-border">
        <div class="col-md-6 col-sm-12">
            <div class="with-border-bottom-sm">
                <h3>Информация о чемпионате</h3>
				<?= $this->render('_form', [
					'model' => $model,
				]) ?>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="with-bottom-border">
                <h3>Классы награждения</h3>
				<?= $this->render('_classes', [
					'model' => $model,
				]) ?>
            </div>
            <h3>Этапы чемпионата</h3>
			<?php foreach ($stages as $stage) { ?>
                <div class="item">
					<?= Html::a($stage->title, ['/competitions/stages/view', 'id' => $stage->id]) ?>
                    &nbsp;
	                <?= Html::a('<span class="fa fa-user btn-my-style btn-light-aquamarine small"></span>',
                        ['/competitions/participants/index', 'stageId' => $stage->id]) ?>
                   <?php if (\Yii::$app->user->can('projectAdmin')) { ?>
                       &nbsp;
	                   <?= Html::a('<span class="fa fa-edit btn-my-style btn-blue small"></span>',
		                   ['/competitions/stages/update', 'id' => $stage->id]) ?>
                    <?php } ?>
                </div>
			<?php } ?>
        </div>
    </div>
	
	
	<?php if ($model->groupId == Championship::GROUPS_REGIONAL) { ?>
		<?= $this->render('_groups-form') ?>
	<?php } ?>
</div>
