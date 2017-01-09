<?php
/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $success string */

$this->title = 'Редактирование страницы: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="page-update">

	<?php if ($success) { ?>
		<div class="alert alert-success"><?= $success ?></div>
	<?php } ?>

	<div class="row">
		<div class="col-sm-6">
			<?= $this->render('//common/_page-form', [
				'model' => $model,
			]) ?>
		</div>
		<div class="col-sm-6">
			<?php if ($children = $model->children) { ?>
				<h3>Дочерние страницы</h3>
				<table>
					<tbody>
					<?php foreach ($children as $child) { ?>
						<tr>
							<td><?= $child->title ?></td>
							<td><?= \common\models\Page::$statusesTitle[$child->status] ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			<?php } ?>
		</div>
	</div>
</div>
