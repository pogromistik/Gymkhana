<?php
use yii\bootstrap\Html;
use yii\widgets\LinkPager;

/**
 * @var \yii\web\View        $this
 * @var array                $figuresArray
 * @var \yii\data\Pagination $pagination
 */
?>
    <h2><?= \Yii::t('app', 'Базовые фигуры') ?></h2>

    <?php if (!\Yii::$app->user->isGuest) { ?>
        <?= Html::a(\Yii::t('app', 'Добавить свой результат'), ['/figures/send-result'], ['class' => 'btn btn-dark']) ?>
    <?php } ?>

    <div class="list">
        <div class="item">
            <div class="toggle">
                <div class="background"></div>
				<?php if (!$figuresArray) { ?>
                    <?= \Yii::t('app', 'В данном разделе пока нет фигур') ?>.
				<?php } else { ?>
					<?php foreach ($figuresArray as $figureData) {
						/** @var \common\models\Figure $figure */
						$figure = $figureData['figure'];
						/** @var \common\models\Year[] $years */
						$years = $figureData['years'];
						?>
                        <div class="item">
                            <div class="toggle">
                                <div class="background"></div>
                                <div class="title">
									<?= $figure->title ?>
                                </div>
                                <div class="info">
									<?php
									if (!$years) { ?>
                                        <?= \Yii::t('app', 'Для фигуры пока нет ни одного результата') ?>.
									<?php } else { ?>
                                        <div class="pl-10">
											<?= Html::a(\Yii::t('app', 'Лучшие результаты за всё время'), ['/competitions/figure', 'id' => $figure->id]) ?>
                                        </div>
										<?php foreach ($years as $year) { ?>
                                            <div class="pl-10">
												<?= Html::a(\Yii::t('app', '{year} год', ['year' => $year->year]),
                                                    ['/competitions/figure', 'id' => $figure->id, 'year' => $year->year]) ?>
                                            </div>
										<?php } ?>
									<?php } ?>
                                </div>
                            </div>
                        </div>
					<?php }
				} ?>
            </div>
        </div>
    </div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>