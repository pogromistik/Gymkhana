<?php

use common\models\Championship;

/**
 * @var \yii\web\View $this
 * @var Championship  $model
 * @var integer       $groupId
 */

$this->title = 'Добавить чемпионат';
$this->params['breadcrumbs'][] = ['label' => 'Разделы чемпионатов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$groupId], 'url' => ['index', 'groupId' => $groupId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="championship-create">
    
    <h3>Создать чемпионат</h3>
	<?= $this->render('_form', [
		'model'   => $model,
		'groupId' => $groupId
	]) ?>
	
	<?php if ($groupId == Championship::GROUPS_REGIONAL) { ?>
		<?= $this->render('_groups-form') ?>
	<?php } ?>

</div>
