<?php
use yii\helpers\Html;

/**
 * @var \common\models\SpecialChamp $championship
 */
?>

<h3><?= $championship->title ?></h3>

<div class="row">
    <div class="col-bg-8 col-lg-9 col-md-10 col-sm-12">
        <b><?= \Yii::t('app', '{year} год', ['year' => $championship->year->year]) ?></b>
        <span class="label label-info"><?= \common\models\SpecialChamp::$statusesTitle[$championship->status] ?></span>
		<?php if ($championship->description) { ?>
            <div class="pt-20">
				<?= $championship->description ?>
            </div>
		<?php } ?>
		
		<div class="pt-10 pb-10">
			<?= \yii\helpers\Html::a(\Yii::t('app', 'Вернуться к списку чемпионатов'),
				['/competitions/results', 'by' => 'russia'], ['class' => 'btn btn-dark']) ?>
        </div>
    </div>

    <div class="col-bg-4 col-lg-3 col-md-2 col-sm-12 list-nav">
		<?php
		$stages = $championship->stages;
		if ($stages) {
			?>
            <ul>
				<?php foreach ($stages as $item) { ?>
                    <li>
						<?= Html::a($item->title, ['/competitions/special-stage', 'id' => $item->id]) ?>
                    </li>
				<?php } ?>
                <li>
					<?= Html::a(\Yii::t('app', 'Итоги чемпионата'), ['/competitions/special-champ-result', 'championshipId' => $championship->id]) ?>
                </li>
            </ul>
			<?php
		}
		?>
    </div>
</div>
