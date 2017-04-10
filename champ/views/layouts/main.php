<?php

/* @var $this \yii\web\View */
/* @var $content string */

use champ\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use champ\assets\AuthorizedAsset;

AppAsset::register($this);
if (!\Yii::$app->user->isGuest) {
    AuthorizedAsset::register($this);
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="description" content="<?= $this->context->description ?>">
    <meta name="keywords" content="<?= $this->context->keywords ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/favicon.ico" sizes="64x64">
	<?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->context->pageTitle) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $this->render('_head') ?>

<h1 style="display: none"><?= $this->context->pageTitle ?></h1>

<div class="container-fluid">
    <div class="content">
        <div id="page-wrapper">
            <div class="breadcrumbs">
				<?= Breadcrumbs::widget([
					'homeLink' => false,
					'links'    => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				]) ?>
            </div>
			<?= $content ?>
        </div>
    </div>
    <!-- /#page-wrapper -->
</div>

<?= $this->render('_footer') ?>

<?= $this->render('_modal') ?>

<?= $this->render('_counters') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
