<?php
use yii\bootstrap\Html;

/**
 * @var \common\models\Notice[] $notices
 */
?>

<?php
foreach ($notices as $notice) { ?>
    <div class="notice">
		<?= $notice->text ?><br>
		<?= Html::a($notice->link, $notice->link) ?>
        <hr>
    </div>
<?php } ?>