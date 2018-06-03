<?php if ($globalStage = \Yii::$app->cache->get('special_stage')) {
	/** @var \common\models\SpecialStage $globalStage */
	?>
	<div class="global-stage">
		<?= \yii\helpers\Html::a($globalStage->getTitle(), ['/competitions/special-stage', 'id' => $globalStage->id]) ?>
	</div>
<?php } ?>