<?php

/* @var $this \yii\web\View */
/* @var $content string */

use admin\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use kartik\sidenav\SideNav;

AppAsset::register($this);
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

<div class="wrap">
    <div class="col-sm-10 content">
		
		<?php if (Yii::$app->session->getFlash('error')) {
			?>
            <div class="pt-20">
                <div class="alert alert-danger">
					<?= Yii::$app->session->getFlash('error'); ?>
                </div>
            </div>
			<?php
		} else { ?>
			<?= Alert::widget() ?>
		<?php }
		?>
	    <?php if (YII_ENV == 'betta') { ?>
            <div class="pt-20">
                <div class="alert alert-danger">
                    <b>Это тестовая версия</b>
                </div>
            </div>
	    <?php } ?>
        <h1><?= Html::encode($this->title) ?></h1>
		<?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
