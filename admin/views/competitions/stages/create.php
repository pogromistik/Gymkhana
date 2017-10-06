<?php
use common\models\Championship;
/**
 * @var \yii\web\View        $this
 * @var \common\models\Stage $model
 * @var int                  $success
 * @var int                  $errorCity
 */

$this->title = 'Создание нового этапа';
$championship = $model->championship;
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$championship->groupId], 'url' => ['/competitions/championships/index', 'groupId' => $championship->groupId]];
$this->params['breadcrumbs'][] = ['label' => $championship->title, 'url' => ['/competitions/championships/view', 'id' => $model->championshipId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stage-create">

    <h3>Создать этап</h3>
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
