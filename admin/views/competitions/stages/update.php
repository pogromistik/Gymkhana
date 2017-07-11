<?php

use yii\helpers\Html;
use common\models\Championship;
use common\models\Stage;

/* @var $this yii\web\View */
/* @var $model common\models\Stage */

$this->title = 'Редактирование этапа: ' . $model->title;
$championship = $model->championship;
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', Championship::$groupsTitle[$championship->groupId]), 'url' => ['/competitions/championships/index', 'groupId' => $championship->groupId]];
$this->params['breadcrumbs'][] = ['label' => $championship->title, 'url' => ['/competitions/championships/view', 'id' => $model->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="stage-update">

    <div class="buttons">
		<?= $this->render('_buttons', ['model' => $model, 'championship' => $championship]) ?>
    </div>
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
