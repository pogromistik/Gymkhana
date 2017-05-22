<?php
/**
 * @var \yii\web\View $this
 */

$this->title = 'Админка сайта';
?>

<div class="alert alert-success">
    <b>Для просмотра руководства пользователя, нажмите на ссылку ниже:</b>
    <ul>
		<?php if (\Yii::$app->user->can('projectOrganizer')) { ?>
            <li><a href="files/Руководство пользователя, уровень - организатор проекта.pdf" target="_blank">Руководство пользователя: организатор проекта</a></li>
		<?php } ?>
		<?php if (\Yii::$app->user->can('projectAdmin')) { ?>
            <li><a href="files/Руководство пользователя, уровень - администратор проекта.pdf" target="_blank">Руководство пользователя: администратор проекта</></li>
		<?php } ?>
        <li><a href="files/Руководство пользователя, уровень - судья соревнования.pdf" target="_blank">Руководство пользователя: судья соревнований</></li>
    </ul>
</div>

<div class="fun-img">
    <div class="row">
        <div class="col-sm-4">
            <img src="/img/0.jpg">
        </div>
        <div class="col-sm-4">
            <img src="/img/1.jpg">
        </div>
        <div class="col-sm-4">
            <img src="/img/2.jpg">
        </div>
    </div>
</div>
