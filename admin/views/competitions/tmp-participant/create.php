<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TmpParticipant */

$this->title = 'Create Tmp Participant';
$this->params['breadcrumbs'][] = ['label' => 'Tmp Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tmp-participant-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
