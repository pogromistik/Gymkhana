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
?>
<div class="championship-update">
	
    <?php if ($success) { ?>
        <div class="alert alert-success">
            Изменения успешно сохранены
        </div>
    <?php } ?>
    
    <div class="pb-20"><?=  Html::a('Добавить этап', ['/competitions/stages/create', 'championshipId' => $model->id], [
		    'class' => 'btn btn-success'
	    ]); ?>
	    <?=  Html::a('Результаты', ['/competitions/championships/results', 'championshipId' => $model->id], [
		    'class' => 'btn btn-default'
	    ]); ?>
    </div>
    
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <h3>Информация о чемпионате</h3>
		    <?= $this->render('_form', [
			    'model' => $model,
		    ]) ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <h3>Классы чемпионата</h3>
		    <?= $this->render('_classes', [
			    'model' => $model,
		    ]) ?>
        </div>
    </div>
	
	
	<?php if ($model->groupId == Championship::GROUPS_REGIONAL) { ?>
		<?= $this->render('_groups-form') ?>
	<?php } ?>
</div>
