<?php
/**
 * @var \yii\web\View                 $this
 * @var \common\models\AssocNews[]    $news
 * @var \common\models\Stage[]        $newStages
 * @var array                         $lastStages
 * @var \common\models\ClassHistory[] $history
 */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\bootstrap\Html;
//use miloschuman\highcharts\Highcharts;

?>
<div class="z-100">
    <div class="row">
        <div class="col-lg-8 col-md-6 col-sm-12">
            <div class="news">
				<?php foreach ($news as $item) {
					$class = 'title-with-bg';
					if ($item->datePublish + 2 * 86400 >= time()) {
						$class .= ' green-title-with-bg';
					} elseif ($item->datePublish + 7 * 86400 >= time()) {
						$class .= ' yellow-title-with-bg';
					}
					?>
                    <div class="item card-box">
						<?php if ($item->title) { ?>
                            <div class="<?= $class ?>">
								<?= $item->title ?>
                            </div>
                            <div class="date"><?= \Yii::$app->formatter->asDate($item->datePublish, "dd.MM.Y") ?></div>
						<?php } else { ?>
                            <div class="<?= $class ?> date"><?= \Yii::$app->formatter->asDate($item->datePublish, "dd.MM.Y") ?></div>
						<?php } ?>
                        <div class="preview_text">
							<?= $item->previewText ?>
                        </div>
						<?php if ($item->link || $item->fullText) {
							if ($item->link) {
								$link = $item->link;
								$target = '_blank';
							} else {
								$link = Url::to(['/site/news', 'id' => $item->id]);
								$target = '_self';
							}
							?>
                            <div class="text-left">
								<?= Html::a(\Yii::t('app', 'Читать далее') . '...', $link, ['target' => $target]) ?>
                            </div>
						<?php } ?>
                    </div>
				<?php } ?>
            </div>
			<?= Html::a(\Yii::t('app', 'Открыть все новости'), ['/site/news-list'], ['class' => 'btn btn-green']) ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 main-stages">
			<?php if ($newStages) { ?>
                <h3><?= \Yii::t('app', 'Открыта регистрация на этапы:') ?></h3>
                <div class="card-box">
					<?php foreach ($newStages as $newStage) { ?>
                        <div class="item">
							<?= Html::a(
								$newStage->getFullTitle() .
								\common\helpers\TranslitHelper::translitCity($newStage->city->title)
								, ['/competitions/stage', 'id' => $newStage->id]) ?>
                        </div>
					<?php } ?>
                </div>
			<?php } ?>
			<?php if ($lastStages) { ?>
                <h3><?= \Yii::t('app', 'Недавние соревнования:') ?></h3>
                <div class="card-box">
					<?php foreach ($lastStages as $lastStage) { ?>
                        <div class="item">
							<?= Html::a($lastStage['title'], $lastStage['link']) ?>
                        </div>
					<?php } ?>
                </div>
			<?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <h3><?= \Yii::t('app', 'Статистика по классам') ?></h3>
            <div class="card-box">
	            <?php
	            /*$people = \Yii::t('app', 'чел.');
	            echo Highcharts::widget([
		            'options' => [
			            'chart'       => [
				            'type'                => 'pie',
				            'plotBackgroundColor' => null,
				            'plotBorderWidth'     => null,
				            'plotShadow'          => null,
				            'spacingTop'          => 0,
				            'spacingLeft'         => 0,
				            'spacingRight'        => 0,
				            'spacingBottom'       => 0
			            ],
			            'title'       => [
				            'text' => ''
			            ],
			            'tooltip'     => [
				            'headerFormat' => '<span style="font-size:11px">{series.name}</span><br>',
				            'pointFormat'  => '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
			            ],
			            'plotOptions' => [
				            'series' => [
					            'dataLabels' => [
						            'enabled' => true,
						            'format'  => "{point.name} ({point.c}{$people})"
					            ]
				            ]
			            ],
			            "series"      => [
				            [
					            "data" => $graphs
				            ]
			            ]
		            ]
	            ]);*/ ?>
            </div>
        </div>
        <div class="col-lg-8 col-md-6 col-sm-12 main-history">
			<?php if ($history) { ?>
                <h3><?= \Yii::t('app', 'История переходов между классами') ?></h3>
                <div class="card-box">
                    <table class="table">
						<?php foreach ($history as $item) {
							$athlete = $item->athlete;
							?>
                            <tr>
                                <td>
									<?= Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                                    <br>
                                    <small class="light-color"><?= $item->dateForHuman ?></small>
                                </td>
                                <td>
									<?php
									$cssClass = 'default';
									$classTitle = $item->oldClass->title;
									if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($classTitle, 'UTF-8')])) {
										$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($classTitle, 'UTF-8')];
									} ?>
                                    <div class="circle-class circle-class-<?= $cssClass ?>"><?= $classTitle ?></div>
                                    &nbsp;<div class="fa fa-arrow-right"></div>&nbsp;
									<?php
									$cssClass = 'default';
									$classTitle = $item->newClass->title;
									if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($classTitle, 'UTF-8')])) {
										$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($classTitle, 'UTF-8')];
									} ?>
                                    <div class="circle-class circle-class-<?= $cssClass ?>"><?= $classTitle ?></div>
                                </td>
                            </tr>
						<?php } ?>
                    </table>
                </div>
			<?php } ?>
        </div>
    </div>
</div>