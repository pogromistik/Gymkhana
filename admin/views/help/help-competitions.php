<?php
/**
 * @var \yii\web\View $this
 */

$this->title = 'Админка сайта';
?>

<div class="alert alert-success">
    <?php if (\Yii::$app->user->can('projectOrganizer')) { ?>
        <b>На этой странице вы можете сказачать подробную инструкцию по функциям админки</b>
        <ul>
            <li>
                <a href="/files/Руководство для организатора.pdf" target="_blank">Руководство для организаторв</a>
            </li>
        </ul>
    <?php } else { ?>
        <b>Руководства пользователя находятся в разработке. Вскоре они появятся на этой странице</b>
    <?php } ?>
</div>

<div class = "fun-img">
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
