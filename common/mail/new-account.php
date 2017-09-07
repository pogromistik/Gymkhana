<?php
/**
 * @var \common\models\Athlete $athlete
 * @var string                 $password
 */
?>

<?= \Yii::t('app', 'Ваша регистрация на сайте {site} подтверждена.',
    ['site' => '<a href="http://gymkhana-cup.ru/" target="_blank">gymkhana-cup.ru</a>']) ?>
<br>
<?= \Yii::t('app', 'Данные для входа в личный кабинет:') ?><br>
<b><?= \Yii::t('app', 'Логин') ?>:</b> <?= $athlete->login ?> <?= \Yii::t('app', 'или') ?> <?= $athlete->email ?><br>
<b><?= \Yii::t('app', 'Пароль') ?>:</b> <?= $password ?><br>
<?= \Yii::t('app', 'Ссылка на вход в личный кабинет') ?>: <a href="http://gymkhana-cup.ru/site/login/" target="_blank">gymkhana-cup.ru/site/login/</a>.<br>
<br>
<?= \Yii::t('app', 'Пожалуйста, проверьте данные профиля в своём кабинете. В случае, если они слишком далеки от реальности - обязательно свяжитесь с нами') ?>:
<br>
<a href="https://vk.com/famalata" target="_blank">vk.com/famalata</a><br>
<a href="https://vk.com/id19792817" target="_blank">vk.com/id19792817</a><br>
<?= \Yii::t('app', 'Или по почте') ?>: lyadetskaya.ns@yandex.ru
