<?php
use yii\bootstrap\Html;
use yii\widgets\LinkPager;

/**
 * @var \yii\web\View        $this
 * @var array                $figuresArray
 * @var \yii\data\Pagination $pagination
 */
?>
    <h2>Базовые фигуры</h2>
    <div class="list">
        <div class="item">
            <div class="toggle">
                <div class="background"></div>
				<?php if (!$figuresArray) { ?>
                    В данном разделе пока нет фигур.
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
                                        Для фигуры пока нет ни одного результата.
									<?php } else { ?>
                                        <div class="pl-10">
											<?= Html::a('Лучшие результаты за всё время', ['/competitions/figure', 'id' => $figure->id]) ?>
                                        </div>
										<?php foreach ($years as $year) { ?>
                                            <div class="pl-10">
												<?= Html::a($year->year . ' год', ['/competitions/figure', 'id' => $figure->id, 'year' => $year->year]) ?>
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