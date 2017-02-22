<?php

use kartik\form\ActiveForm;
use kartik\sortinput\SortableInput;

/**
 * @var array                $participantsArray
 * @var \common\models\Stage $stage
 * @var \yii\web\View        $this
 */

$this->title = 'Порядок выступления участников';
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['/competitions/stages/view', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = ['label' => 'Участники', 'url' => ['/competitions/participants/index', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>
<?= SortableInput::widget([
	'name'            => 'sort_list',
	'hideInput'       => true,
	'items'           => $participantsArray,
	'sortableOptions' => [
		'id'           => 'w1',
		'pluginEvents' => [
			'sortupdate' => 'function(e){
				showBackDrop();
				console.log("sortupdate");
				$.ajax({
			        url: "/competitions/participants/change-sort",
			        type: "POST",
			        data: $("#w0").serialize(),
			        success: function (result) {
			            if (result == true) {
			                console.log(result);
			                hideBackDrop();
			            } else {
			                console.log(result);
			                hideBackDrop();
			            }
			        },
			        error: function (result) {
			            console.log(result);
			             hideBackDrop();
			        }
			    });
			}',
		]
	]
]);
?>
<?php $form->end() ?>
