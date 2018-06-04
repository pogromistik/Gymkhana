<?php
/**
 * @var \yii\web\View $title
 * @var array         $stats
 * @var array         $classes
 * @var array         $totalClasses
 */
?>

    <h2><?= $this->context->pageTitle ?></h2>
    <div class="card-box">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th><?= \Yii::t('app', \Yii::t('app', 'Регион')) ?></th>
				    <?php foreach ($classes as $class) { ?>
                        <th class="text-center"><?= $class ?></th>
				    <?php } ?>
                    <th><?= \Yii::t('app', \Yii::t('app', 'Всего')) ?></th>
                </tr>
                </thead>

                <tbody>
			    <?php foreach ($stats as $region => $data) { ?>
                    <tr>
                        <td><?= \common\helpers\TranslitHelper::translitRegion($region) ?>, <?= $data['country'] ?></td>
					    <?php foreach ($classes as $class) { ?>
                            <td class="text-center"><?= (isset($data['groups'][$class]) && $data['groups'][$class] > 0) ? $data['groups'][$class] : null ?></td>
					    <?php } ?>
                        <td><?= $data['total'] ?></td>
                    </tr>
			    <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <th><?= \Yii::t('app', \Yii::t('app', 'Итого по России')) ?></th>
				    <?php foreach ($classes as $class) { ?>
                        <th class="text-center"><?= isset($totalClasses[$class]) ? $totalClasses[$class] : null ?></th>
				    <?php } ?>
                    <th><?= $totalClasses['total'] ?></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?= \yii\helpers\Html::a(\Yii::t('app', \Yii::t('app', 'Вернуться к списку спортсменов')), ['/athletes/list']) ?>
