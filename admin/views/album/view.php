<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Album */

$this->title = $model->title;
$this->params['breadcrumbs'][] = 'Галерея';
$this->params['breadcrumbs'][] = ['label' => 'Альбомы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверены, что хотите удалить альбом со всеми фотографиями?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            [
                'attribute' => 'yearId',
                'format' => 'raw',
                'value' => $model->year ? $model->year->year : null
            ],
            'folder',
            [
                'attribute' => 'dateAdded',
                'format' => 'raw',
                'value' => date("d.m.Y, H:i", $model->dateAdded)
            ],
            [
                'attribute' => 'cover',
                'format' => 'raw',
                'value' => $model->cover ? Html::img(Yii::getAlias('@filesView') . $model->cover) : null
            ]
        ],
    ]) ?>

    <?php
    $photos = $model->getPhotos();
    if ($photos) {
        ?>
        <table class="table">
            <tbody>
            <?php foreach ($photos as $photo) {?>
                <tr>
                    <td><?= Html::img(Yii::getAlias('@filesView') . '/' . $model->folder . '/' . $photo) ?></td>
                    <td><a href="#" data-id="<?=$model->folder . '/' . $photo?>" class="delete-album-photo">Удалить</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php
    }
    ?>

</div>
