<?php
/**
 * @var \yii\web\View $this
 */
use common\models\Championship;
use yii\bootstrap\Html;

$this->title = 'Выберите раздел Чемпионата';
?>

<ul>
<?php foreach (Championship::$groupsTitle as $groupId => $title) { ?>
	<li><?= Html::a(\Yii::t('app', $title), ['index', 'groupId' => $groupId]) ?></li>
<?php } ?>
</ul>
