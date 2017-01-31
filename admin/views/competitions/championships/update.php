<?php

use common\models\Championship;

/**
 * @var \yii\web\View $this
 * @var Championship  $model
 * @var integer       $success
 */

/* @var $this yii\web\View */
/* @var $model common\models\Championship */

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
    
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
	
	<?php if ($model->groupId == Championship::GROUPS_REGIONAL) { ?>
		<?= $this->render('_groups-form') ?>
	<?php } ?>
</div>
