<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View        $this
 * @var \common\models\Stage $stage
 * @var array                $errors
 * @var array                $success
 */

$this->title = 'Порядок выступления спортсменов изменён';
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['/competitions/stages/view', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = ['label' => 'Участники', 'url' => ['/competitions/participants/index', 'stageId' => $stage->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if ($errors) { ?>
    <div class="pb-10">
        <div class="alert alert-danger">
            <b>При изменении порядка выступления возникли ошибки:</b>
            <br>
            <ul>
				<?php foreach ($errors as $error) { ?>
                    <li><?= $error ?></li>
				<?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>

<?php if ($success) { ?>
    <div class="alert alert-success">
        <b>Порядок выступления успешно изменён</b>
        <ul>
			<?php foreach ($success as $sort => $name) { ?>
                <li><?= $sort ?> - <?= $name ?></li>
			<?php } ?>
        </ul>
    </div>
<?php } ?>
