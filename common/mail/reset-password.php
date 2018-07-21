<?php
/**
 * @var string $resetLink
 */
?>

<?= \Yii::t('app', 'Для восстановления пароля от личного кабинета на сайте {site} пройдите по ссылке:',
    ['site' => '<a href="http://gymkhana-cup.ru/" target="_blank">gymkhana-cup.ru</a>']) ?>
<br>
<a href="<?= $resetLink ?>" target="_blank"><?= $resetLink ?></a>

<?= $this->render('_footer', ['language' => \Yii::$app->language]) ?>