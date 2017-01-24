<?php

use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Album */

$this->title = 'Редактирование альбома: ' . $model->title;
$this->params['breadcrumbs'][] = 'Галерея';
$this->params['breadcrumbs'][] = ['label' => 'Альбомы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="album-update">
    
    <div class="alert alert-info">Добавить фотографии</div>

    <p>
        <?=Html::a('Все фотографии', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </p>

    <div class="alert alert-info">
        Максимальное число загружаемых файлов: 20
    </div>
    
    <?= FileInput::widget([
        'name'          => 'albums_photo[]',
        'options'       => [
            'multiple' => true,
            'accept'   => 'image/*',
        ],
        'pluginOptions' => [
            'uploadUrl'    => Url::to(['base/upload-album-pictures', 'folder' => $model->folder]),
            'maxFileCount' => 20
        ]
    ]);
    ?>

    <br>
    
    <div class="alert alert-info">Редактировать альбом</div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
