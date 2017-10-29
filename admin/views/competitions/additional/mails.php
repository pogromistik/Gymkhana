<?php
/**
 * @var \yii\web\View $this
 */

$this->title = 'Текста писем';
?>

    <div class="alert help-alert alert-info">
        <div class="text-right">
            <span class="fa fa-remove closeHintBtn"></span>
        </div>
        На данной странице отображаются текста всех писем, которые могут быть когда-либо отправлены спортсмену на его
        email.
        Внешнний вид, пунктуация, орфография совпадают с тем, что увидит человек. Слова в скобках {} будут заменены на
        соответствующие значения.<br>
        Если вы не согласны с какими-либо формулировками - свяжитесь с <a href="https://vk.com/id19792817"
                                                                          target="_blank">разработчиком</a>.
    </div>

<?php
$athlete = \common\models\Athlete::find()->where(['hasAccount' => 1])->one();
if ($athlete) {
	?>
    <div class="with-bottom-border">
        <h3>1. Создание личного кабинета</h3>
        <div class="mail-text">
			<?= $this->render('@common/mail/new-account', ['athlete' => $athlete, 'password' => 'test']) ?>
        </div>
    </div>
<?php } ?>

    <div class="with-bottom-border">
        <h3>2. Предварительная регистрация на этап
            <small>(если этап с ограниченным количеством участников)</small>
        </h3>
        <div class="mail-text">
            Предварительная регистрация на этап принята.<br>
            <hr>
            <small>&#60;следующий текст будет отправлен, если регистрация была не из личного кабинета&#62;</small>
            <br>
            В случае отклонения вашей заявки, вам будет отправлено соответствующее письмо на этот email и уведомление в
            личный
            кабинет.<br>
            При успешном подтверждении заявки - только уведомление.<br>
            <hr>
            <small>&#60;следующий текст будет отправлен, если регистрация была из личного кабинета&#62;</small>
            <br>
            В случае отклонения вашей заявки, будет отправлено соответствующее письмо на этот email.<br>
            На сайте <a href="http://gymkhana-cup.ru" target="_blank">gymkhana-cup.ru</a> неподтверждённые заявки
            выделены
            серым
            цветом.
            Если ваша заявка другого цвета - значит, ваше участие подтверждено.
            <hr>
            <br><br>
            <b>Чемпионат:</b> {название чемпионата}<br>
            <b>Этап:</b> {название этапа}<br>
            <b>Участник:</b> {Имя Фамилия}<br>
            <b>Мотоцикл:</b> {Марка Модель}
        </div>
    </div>

    <div class="with-bottom-border">
        <h3>3. Восстановление пароля</h3>
        Для восстановления пароля от личного кабинета на сайте gymkhana-cup.ru пройдите по ссылке:
        <br>
        {ссылка для восстановления пароля}
    </div>


<?php
$figure = \common\models\Figure::find()->where(['not', ['bestTimeInRussia' => null]])->one();
if ($figure) {
	?>
    <div class="with-bottom-border">
        <h3>4. Рассылка: новый Российский рекорд</h3>
		<?= $this->render('@common/mail/subscriptions/_content',
			['msgType' => \common\models\NewsSubscription::MSG_FOR_RUSSIA_RECORDS, 'model' => $figure, 'token' => 'token',
			 'language' => \common\models\TranslateMessage::LANGUAGE_RU]) ?>
    </div>
<?php } ?>

<?php
$figure = \common\models\Figure::find()->where(['not', ['bestTime' => null]])->one();
if ($figure) {
	?>
    <div class="with-bottom-border">
        <h3>5. Рассылка: новый мировой рекорд</h3>
		<?= $this->render('@common/mail/subscriptions/_content',
			['msgType'  => \common\models\NewsSubscription::MSG_FOR_WORLD_RECORDS, 'model' => $figure, 'token' => 'token',
			 'language' => \common\models\TranslateMessage::LANGUAGE_RU]) ?>
    </div>
<?php } ?>

<?php
$stage = \common\models\Stage::find()->where(['not', ['dateOfThe' => null]])->andWhere(['not', ['description' => null]])
	->andWhere(['!=', 'description', ''])->orderBy(['dateAdded' => SORT_DESC])->one();
if ($stage) {
	?>
    <div class="with-bottom-border">
        <h3>6. Рассылка: Анонс этапа</h3>
		<?= $this->render('@common/mail/subscriptions/_content',
			['msgType'  => \common\models\NewsSubscription::MSG_FOR_STAGE, 'model' => $stage, 'token' => 'token',
			 'language' => \common\models\TranslateMessage::LANGUAGE_RU]) ?>
    </div>
<?php } ?>

<?php
$stage = \common\models\SpecialStage::find()->one();
if ($stage) {
	?>
    <div class="with-bottom-border">
        <h3>7. Рассылка: Анонс особого этапа</h3>
		<?= $this->render('@common/mail/subscriptions/_content',
			['msgType'  => \common\models\NewsSubscription::MSG_FOR_SPECIAL_STAGE, 'model' => $stage, 'token' => 'token',
			 'language' => \common\models\TranslateMessage::LANGUAGE_RU]) ?>
    </div>
<?php } ?>

<?php
$stage = \common\models\Stage::find()->where(['not', ['startRegistration' => null]])->one();
if ($stage) {
	?>
    <div class="with-bottom-border">
        <h3>8. Рассылка: Открыта регистрация на этап</h3>
		<?= $this->render('@common/mail/subscriptions/_content',
			['msgType'  => \common\models\NewsSubscription::MSG_FOR_REGISTRATIONS, 'model' => $stage, 'token' => 'token',
			 'language' => \common\models\TranslateMessage::LANGUAGE_RU]) ?>
    </div>
<?php } ?>

<?php
$stage = \common\models\SpecialStage::find()->where(['not', ['dateStart' => null]])->one();
if ($stage) {
	?>
    <div class="with-bottom-border">
        <h3>9. Рассылка: Начат приём результатов на особый этап</h3>
		<?= $this->render('@common/mail/subscriptions/_content',
			['msgType'  => \common\models\NewsSubscription::MSG_FOR_SPECIAL_REGISTRATIONS, 'model' => $stage, 'token' => 'token',
			 'language' => \common\models\TranslateMessage::LANGUAGE_RU]) ?>
    </div>
<?php } ?>