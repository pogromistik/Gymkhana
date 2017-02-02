<?php

use common\models\Championship;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var Championship  $model
 * @var integer       $success
 */

$this->title = 'Редактировать чемпионат: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Разделы чемпионатов', 'url' => ['index']];
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
	    'class' => 'btn btn-success',
	    'title' => 'Просмотр'
        ]); ?></div>
    
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
	
	<?php if ($model->groupId == Championship::GROUPS_REGIONAL) { ?>
		<?= $this->render('_groups-form') ?>
	<?php } ?>
</div>
