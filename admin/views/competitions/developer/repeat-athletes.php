<?php
/**
 * @var \yii\web\View            $this
 * @var \common\models\Athlete[] $athletes
 */
$this->title = 'Повторы спортсменов по фамилии';
?>

<?php
$lastName = null;
foreach ($athletes as $athlete) {
	if ($lastName && $lastName != $athlete->lastName) { ?>
        <hr>
	<?php } ?>
    <div class="row">
        <div class="col-xs-4">
			<?= \yii\helpers\Html::a($athlete->getFullName(), ['/competitions/athlete/update', 'id' => $athlete->id]) ?>
        </div>
        <div class="col-xs-4">
			<?= $athlete->city->title ?>
        </div>
        <div class="col-xs-4">
			<?php foreach ($athlete->activeMotorcycles as $motorcycle) { ?>
                <div><?= $motorcycle->getFullTitle() ?></div>
			<?php } ?>
        </div>
    </div>
    <?php $lastName = $athlete->lastName; ?>
<?php } ?>

