<?php
use common\models\Figure;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View               $this
 * @var Figure                      $figure
 * @var \common\models\FigureTime[] $results
 * @var \common\models\Year | null  $year
 * @var bool                        $showAll
 * @var array                       $needTime
 */
$time = time();
?>

<div class="row figure">
    <div class="col-bg-7 col-lg-9 col-md-10 col-sm-12">
        <div class="title-with-bg">
			<?= $figure->title ?>
			<?php if ($year) { ?>
                , <?= $year->year ?>
			<?php } ?>
        </div>

        <div class="pt-20">
            <div class="description pb-20 card-box">
				<?php if ($figure->description) { ?>
                    <p><?= $figure->description ?></p>
				<?php } ?>
                <div class="records">
                    <b><?= \Yii::t('app', 'Мировой рекорд') ?>:</b>
					<?php if ($figure->bestAthlete) { ?>
						<?= \common\helpers\TranslitHelper::translitFio($figure->bestAthlete) ?>
					<?php } ?>
					<?= $figure->bestTimeForHuman ?>
					<?php if ($figure->bestAthleteInRussia || $figure->bestTimeInRussia) { ?>
                        <br>
                        <b><?= \Yii::t('app', 'Рекорд в России') ?>:</b>
						<?php if ($figure->bestAthleteInRussia) { ?>
							<?= \common\helpers\TranslitHelper::translitFio($figure->bestAthleteInRussia) ?>
						<?php } ?>
						<?php if ($figure->bestTimeInRussia) { ?>
							<?= $figure->bestTimeInRussiaForHuman ?>
						<?php } ?>
					<?php } ?>
                </div>
				<?php if ($figure->picture) { ?>
                    <div class="track-photo">
                        <div class="toggle">
                            <div class="title"><?= \Yii::t('app', 'Посмотреть схему фигуры') ?></div>
                            <div class="toggle-content">
								<?= \yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $figure->picture) ?>
                            </div>
                        </div>
                    </div>
				<?php } ?>
            </div>

            <div>
				<?php if ($needTime && $figure->useForClassesCalculate) { ?>
                    <div class="card-box">
                        <?= \Yii::t('app', 'Время, необходимое для повышения класса') ?>:
                        <table class="table">
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <?= \Yii::t('app', 'Класс') ?>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <?= \Yii::t('app', 'Процент') ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <?= \Yii::t('app', 'Минимальное время') ?>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <?= \Yii::t('app', 'Максимальное время') ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
							<?php foreach ($needTime as $id => $data) {
								$cssClass = null;
								if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($data['classModel']->title, 'UTF-8')])) {
									$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($data['classModel']->title, 'UTF-8')];
								}
								?>
                                <tr class="result-<?= $cssClass ?>">
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-12">
												<?= $data['classModel']->title ?>
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
												<?= $data['percent'] ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-12">
												<?= $data['startTime'] ?>
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
												<?= $data['endTime'] ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
							<?php } ?>
                        </table>
                    </div>
				<?php } ?>
            </div>
			
			<?php if (\Yii::$app->user->isGuest) { ?>
                <div class="pb-10"><?= \Yii::t('app', 'Отправка результатов на сайт доступна только зарегистрированным пользователям') ?></div>
			<?php } ?>
			
			<?php if (!$figure->useForClassesCalculate) { ?>
                <div class="pb-10"><b>
                        <?= \Yii::t('app', 'По данной фигуре не производится смена класса') ?>.
                    </b></div>
			<?php } ?>

            <div class="filters card-box">
				<?= \yii\bootstrap\Html::beginForm('', 'post', ['id' => 'figureFilterForm']) ?>
				<?= \yii\bootstrap\Html::hiddenInput('figureId', $figure->id) ?>
				<?= \yii\bootstrap\Html::hiddenInput('yearId', $year ? $year->id : null) ?>
				<?= \yii\bootstrap\Html::hiddenInput('showAll', $showAll, ['id' => 'showAll']) ?>
                <div class="row">
                    <div class="col-md-12 pb-10-md input-with-sm-pt">
						<?= Select2::widget([
							'name'    => 'countryId',
							'data'    => \common\models\Country::getAll(true),
							'options' => [
								'placeholder' => \Yii::t('app', 'Выберите страну') . '...',
								'id'          => 'country-id',
							],
						]) ?>
                    </div>
                    <div class="col-md-6 col-sm-12 input-with-sm-pt">
						<?= \kartik\widgets\DepDrop::widget([
							'name'           => 'regionIds',
							'data'           => [],
							'options'        => ['placeholder' => \Yii::t('app', 'Выберите регион') . '...'],
							'type'           => \kartik\widgets\DepDrop::TYPE_SELECT2,
							'select2Options' => ['pluginOptions' => ['allowClear' => true, 'multiple' => true]],
							'pluginOptions'  => [
								'depends'     => ['country-id'],
								'url'         => \yii\helpers\Url::to(['/help/country-category', 'type' => \champ\controllers\HelpController::TYPE_REGION]),
								'loadingText' => \Yii::t('app', 'Для выбранной страны не найдено городов') . '...',
								'placeholder' => \Yii::t('app', 'Выберите регион') . '...'
							],
							'pluginEvents'   => [
								'change' => 'function(e){
				figureFilters();
			}',
							]
						]);
						?>
						<?php /*
				<?= Select2::widget([
					'name'          => 'regionIds',
					'data'          => \common\models\Region::getAll(true),
					'maintainOrder' => true,
					'options'       => ['placeholder' => 'Выберите регион...', 'multiple' => true],
					'pluginOptions' => [
						'tags'               => true,
						'maximumInputLength' => 10
					],
					'pluginEvents'  => [
						'change' => 'function(e){
				figureFilters();
			}',
					]
				]);
				?>
 */
						?>
                    </div>
                    <div class="col-md-6 col-sm-12 input-with-sm-pt">
						<?= Select2::widget([
							'name'          => 'classIds',
							'data'          => \yii\helpers\ArrayHelper::map(\common\models\AthletesClass::find()
								->where(['status' => \common\models\AthletesClass::STATUS_ACTIVE])
								->orderBy(['percent' => SORT_ASC])->all(), 'id', 'title'),
							'maintainOrder' => true,
							'options'       => ['placeholder' => \Yii::t('app', 'Выберите класс') . '...', 'multiple' => true],
							'pluginOptions' => [
								'tags'               => true,
								'maximumInputLength' => 10
							],
							'pluginEvents'  => [
								'change' => 'function(e){
				figureFilters();
			}',
							]
						]);
						?>
                    </div>
                    <div class="col-md-12 col-sm-12 pt-10-md input-with-sm-pt">
						<?= \kartik\widgets\DatePicker::widget([
							'name'          => 'date',
							'type'          => \kartik\widgets\DatePicker::TYPE_INPUT,
							'value'         => null,
							'options'       => ['placeholder' => \Yii::t('app', 'Выберите дату') . '...'],
							'readonly'      => true,
							'pluginOptions' => [
								'autoclose' => true,
								'format'    => 'dd.mm.yyyy'
							],
							'pluginEvents'  => [
								'change' => 'function(e){
				figureFilters();
			}'],
						]); ?>
                    </div>
                </div>
				<?php \yii\bootstrap\Html::endForm() ?>
            </div>

            <div class="alert alert-danger" style="display: none"></div>

            <div class="results pt-20">
                <div class="row">
                    <div class="col-sm-6 col-xs-12 pb-10">
						<?= \yii\bootstrap\Html::a(\Yii::t('app', 'Скачать в xls'), \yii\helpers\Url::to([
							'/export/export',
							'modelId' => $figure->id,
							'type'    => \champ\controllers\ExportController::TYPE_FIGURE,
							'yearId'  => $year ? $year->id : null
						]), ['class' => 'btn btn-light']) ?>
                    </div>
                    <div class="col-sm-6 col-xs-12 pb-10 text-right">
						<?php if (!\Yii::$app->user->isGuest) { ?>
							<?= \yii\bootstrap\Html::a(\Yii::t('app', 'Добавить результат'), \yii\helpers\Url::to([
								'/figures/send-result',
								'figureId' => $figure->id
							]), ['class' => 'btn btn-dark']) ?>
						<?php } ?>
                    </div>
                </div>
				
				<?php if ($figure->severalRecords) { ?>
                   <div class="card-box">
	                   <?= \Yii::t('app', 'С момента добавления результатов мировой рекорд по фигуре {figureTitle} был обновлён. В связи с этим, в таблице выводится два рейтинга',
		                   ['figureTitle' => $figure->title]) ?>:<br>
                       — <?= \Yii::t('app', '"Начальный рейтинг" - рейтинг на момент добавления результата') ?><br>
                       — <?= \Yii::t('app', '"Актуальный рейтинг" - процент отставания от текущего мирового рекорда') ?><br>
                       <div class="show-pk">
		                   <?= \Yii::t('app', 'При наведении на начальный рейтинг показывается рекорд, который был в момент добавления результата на сайт.') ?>
                       </div>
                   </div>
				<?php } ?>

                <div class="small text-right">
					<?php $count = count($results); ?>
					<?php if ($count == 30) { ?>
                       <?= \Yii::t('app', 'Показаны 30 лучших результатов') ?>. <a href="#" class="showAll"><?= \Yii::t('app', 'Показать все') ?></a>
					<?php } else { ?>
                        <?= \Yii::t('app', 'Количество результатов: {count}', ['count' => $count]) ?>
					<?php } ?>
                    <br><small><?= \Yii::t('app', 'для просмотра прогресса по фигуре нажмите на итоговое время') ?></small>
                </div>
				
				<?php if ($figure->useForClassesCalculate) { ?>
					<?= $this->render('_figure-result', ['results' => $results, 'figure' => $figure]) ?>
				<?php } else { ?>
					<?= $this->render('_figure-result-without-class', ['results' => $results, 'figure' => $figure]) ?>
				<?php } ?>
            </div>
        </div>

        <a href="<?= \yii\helpers\Url::to(['/competitions/results', 'by' => \champ\controllers\CompetitionsController::RESULTS_FIGURES]) ?>">
            <?= \Yii::t('app', 'Вернуться к фигурам') ?></a>
    </div>

    <div class="col-bg-5 col-lg-3 col-md-2 col-sm-12 list-nav">
		<?php
		$all = Figure::getAll($figure->id);
		if ($all) {
			?>
            <ul>
				<?php
				if (!$year) { ?>
					<?php foreach ($all as $one) { ?>
                        <li>
							<?= Html::a($one->title, ['/competitions/figure', 'id' => $one->id]) ?>
                        </li>
					<?php } ?>
				<?php } else { ?>
					<?php foreach ($all as $one) { ?>
                        <li>
							<?= Html::a($one->title, ['/competitions/figure', 'id' => $one->id, 'year' => $year->year]) ?>
                        </li>
					<?php }
				}
				?>
            </ul>
			<?php
		}
		?>
    </div>
</div>