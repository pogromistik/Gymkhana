<?php
/**
 * @var \yii\web\View $title
 * @var array         $stats
 * @var array         $classes
 * @var array         $totalClasses
 */
$this->title = 'Статистика по регионам';
?>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Регион</th>
			<?php foreach ($classes as $class) { ?>
                <th class="text-center"><?= $class ?></th>
			<?php } ?>
            <th>Всего</th>
        </tr>
        </thead>

        <tbody>
		<?php foreach ($stats as $region => $data) { ?>
            <tr>
                <td><?= $region ?></td>
				<?php foreach ($classes as $class) { ?>
                    <td class="text-center"><?= (isset($data['groups'][$class]) && $data['groups'][$class] > 0) ? $data['groups'][$class] : null ?></td>
				<?php } ?>
                <td><?= $data['total'] ?></td>
            </tr>
		<?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <th>Итого по России</th>
            <?php foreach ($classes as $class) { ?>
                <th class="text-center"><?= isset($totalClasses[$class]) ? $totalClasses[$class] : null ?></th>
            <?php } ?>
            <th><?= $totalClasses['total'] ?></th>
        </tr>
        </tfoot>
    </table>
</div>
