<?php
/**
 * @var \yii\web\View              $this
 * @var \common\models\Interview[] $interviews
 * @var \yii\data\Pagination       $pagination
 */

use yii\widgets\LinkPager;
use yii\bootstrap\Html;

?>
<h2><?= \Yii::t('app', 'Опросы') ?></h2>
<div class="z-100">
    <div class="interviews">
        <table class="table">
			<?php foreach ($interviews as $interview) {
				?>
                <tr>
                    <td><?= Html::a($interview->getTitle(), ['/interviews/view', 'id' => $interview->id]) ?></td>
                    <td><?= Html::a('подробнее...', ['/interviews/view', 'id' => $interview->id],
							['class' => 'btn btn-green btn-xs']) ?></td>
                </tr>
			<?php } ?>
        </table>
    </div>

    <div class="text-left">
		<?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>
</div>