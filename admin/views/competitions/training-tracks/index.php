<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TrainingTrackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Тренировочные трассы';
?>
<div class="training-track-index">

    <p>
        <?= Html::a('Добавить трассу', ['create'], ['class' => 'btn btn-my-style btn-green']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'imgPath',
                'filter'    => false,
                'format'    => 'raw',
                'value'     => function (\common\models\TrainingTrack $track) {
                    return Html::img(\Yii::getAlias('@filesView') . '/' . $track->imgPath);
                }
            ],
            [
                'attribute' => 'level',
                'filter'    => Html::activeDropDownList($searchModel, 'level',
                    \common\models\TrainingTrack::$levelTitles,
                    ['class' => 'form-control', 'prompt' => 'Уровень']),
                'value'     => function (\common\models\TrainingTrack $track) {
                    return \common\models\TrainingTrack::$levelTitles[$track->level];
                }
            ],
            'minWidth',
            'minHeight',
            'conesCount',
            [
                'attribute' => 'status',
                'filter'    => Html::activeDropDownList($searchModel, 'status',
                    \common\models\TrainingTrack::$statusTitles,
                    ['class' => 'form-control', 'prompt' => 'Статус']),
                'value'     => function (\common\models\TrainingTrack $track) {
                    return \common\models\TrainingTrack::$statusTitles[$track->status];
                }
            ],
            [
                'format' => 'raw',
                'value'  => function (\common\models\TrainingTrack $track) {
                    return Html::a('<span class="fa fa-check"></span>', 'javascript:;', [
                        'class'   => 'btn btn-my-style btn-green btn-xs approveTrack',
                        'data-id' => $track->id
                    ]);
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
