<?php
use yii\bootstrap\Html;

/**
 * @var \yii\web\View        $this
 * @var \common\models\Stage $stage
 */

$this->title = 'Загрузить порядок выступления участников';
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['/competitions/stages/view', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = ['label' => 'Участники', 'url' => ['/competitions/participants/index', 'stageId' => $stage->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-info">
    <b>Для того, чтобы загрузить порядок выступления спортсменов из файла:</b><br>
    1. Скачайте файл с участниками: <?= \yii\helpers\Html::a('скачать',
		['/competitions/xls/get-xls', 'type' => \admin\controllers\competitions\XlsController::TYPE_ALL, 'stageId' => $stage->id]) ?>
    <br>
    2. Заполните столбец "Порядок выступления"<br>
    3. Загрузите файл в форму ниже<br>
    <b>Внимание!</b> Файл имеет определённый формат. Не меняйте местами колонки и не удаляйте название столбцов. Для
    загрузки
    порядка выступления необходимы параметры: "ID" (A), "Порядок выступления" (B), "Участник" (C).
</div>

<?= Html::beginForm(['/competitions/participants/sort-upload-processed', 'stageId' => $stage->id], 'post', [
	'enctype' => 'multipart/form-data'
]); ?>
<div class="form-group">
	<?= Html::label('Файл', 'file') ?>
	<?= Html::fileInput('file') ?> <br/>
</div>
<?= Html::submitButton('Загрузить', ['class' => 'btn btn-my-style btn-light-aquamarine']) ?>
<?= Html::endForm(); ?>
