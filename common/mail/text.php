<?php
/**
 * @var string $text
 */

if (!isset($language)) {
	$language = \Yii::$app->language;
}

echo $text;
?>

<?= $this->render('_footer', ['language' => $language]) ?>
