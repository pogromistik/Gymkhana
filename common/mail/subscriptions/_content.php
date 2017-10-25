<?php
use common\models\NewsSubscription;

/**
 * @var integer              $msgType
 * @var \yii\db\ActiveRecord $model
 * @var string               $token
 * @var \yii\web\View        $this
 * @var string               $language
 */
$content = '';

switch ($msgType) {
	case NewsSubscription::MSG_FOR_RUSSIA_RECORDS:
		$content = $this->renderAjax('russiaRecord', ['model' => $model, 'token' => $token, 'language' => $language]);
		break;
	case NewsSubscription::MSG_FOR_WORLD_RECORDS:
		$content = $this->renderAjax('worldRecord', ['model' => $model, 'token' => $token, 'language' => $language]);
		break;
	case NewsSubscription::MSG_FOR_STAGE:
		$content = $this->renderAjax('stage', ['model' => $model, 'token' => $token, 'language' => $language]);
		break;
	case NewsSubscription::MSG_FOR_SPECIAL_STAGE:
		$content = $this->renderAjax('specialStage', ['model' => $model, 'token' => $token, 'language' => $language]);
		break;
	case NewsSubscription::MSG_FOR_REGISTRATIONS:
		$content = $this->renderAjax('stageRegistration', ['model' => $model, 'token' => $token, 'language' => $language]);
		break;
	case NewsSubscription::MSG_FOR_SPECIAL_REGISTRATIONS:
		$content = $this->renderAjax('specialStageRegistration', ['model' => $model, 'token' => $token, 'language' => $language]);
		break;
}
?>

<?= $this->render('_layout', ['content' => $content, 'language' => $language]) ?>
