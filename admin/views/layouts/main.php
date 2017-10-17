<?php

/* @var $this \yii\web\View */
/* @var $content string */

use admin\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use \admin\assets\BootboxAsset;
use common\models\Error;

AppAsset::register($this);

$criticalErrors = Error::findOne(['status' => Error::STATUS_NEW, 'type' => Error::TYPE_CRITICAL_ERROR]);
$errors = Error::findAll(['status' => Error::STATUS_NEW]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="wrapper">
	<?= $this->render('_navigation') ?>

    <div id="page-wrapper">
	    <?php if (YII_ENV == 'betta') { ?>
            <div class="pt-20">
                <div class="alert alert-danger">
                    <b>Это тестовая версия</b>
                </div>
            </div>
	    <?php } ?>
		<?php if (\Yii::$app->user->can('developer')) { ?>
			<?php if ($errors) { ?>
                <div class="pt-20">
                    <div class="alert alert-danger">
						<?php if (count($errors) <= 3) { ?>
                            <ul>
								<?php foreach ($errors as $error) { ?>
                                    <li><?= $error->text ?></li>
								<?php } ?>
                            </ul>
							<?= Html::a('Посмотреть список ошибок', ['/admin/errors-list']) ?>
						<?php } else { ?>
							<?= Html::a('Посмотреть список', ['/admin/errors-list']) ?>
						<?php } ?>
                    </div>
                </div>
			<?php } ?>
		<?php } else { ?>
			<?php if ($criticalErrors) { ?>
                <div class="pt-20">
                    <div class="alert alert-danger">
                        На сайте обнаружены критические ошибки. Пожалуйста, свяжитесь с
                        <a href="https://vk.com/id19792817" target="_blank">разработчиками</a>
                        для их устранения.
                    </div>
                </div>
			<?php } ?>
		<?php } ?>
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?= $this->title ?></h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="breadcrumbs">
			<?= Breadcrumbs::widget([
				'homeLink' => false,
				'links'    => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
        </div>
		<?= $content ?>
    </div>
    <!-- /#page-wrapper -->

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
