<?php
/**
 * @var \common\models\Athlete $athlete
 * @var string                 $password
 */
switch ($athlete->language) {
    case \common\models\TranslateMessage::LANGUAGE_RU:
        $hostName = 'gymkhana-cup.ru';
        break;
    default:
	    $hostName = 'gymkhana-cup.com';
}
?>

<?= \Yii::t('app', 'Ваша регистрация на сайте {site} подтверждена.',
	['site' => '<a href="https://' . $hostName . '/" target="_blank">' . $hostName . '</a>'], $athlete->language) ?>
<br>
<?= \Yii::t('app', 'Данные для входа в личный кабинет:', [], $athlete->language) ?><br>
<b><?= \Yii::t('app', 'Логин', [], $athlete->language) ?>:</b> <?= $athlete->login ?> <?= \Yii::t('app', 'или', [], $athlete->language) ?> <?= $athlete->email ?><br>
<b><?= \Yii::t('app', 'Пароль', [], $athlete->language) ?>:</b> <?= $password ?><br>
<?= \Yii::t('app', 'Ссылка на вход в личный кабинет', [], $athlete->language) ?>: <a href="https://<?= $hostName ?>/site/login/"
                                                             target="_blank"><?= $hostName ?>/site/login/</a>.
<br>
<br><?= \Yii::t('app', 'Пожалуйста, проверьте данные профиля в своём кабинете. В случае, если они слишком далеки от реальности - обязательно свяжитесь с нами', [], $athlete->language) ?>:
<br>
<a href="https://vk.com/famalata" target="_blank">vk.com/famalata</a><br>
<a href="https://vk.com/id19792817" target="_blank">vk.com/id19792817</a><br>
<?= \Yii::t('app', 'Или по почте', [], $athlete->language) ?>: gymkhana.cup@gmail.com

<?= $this->render('_footer', ['language' => \Yii::$app->language]) ?>
