<?php

/* @var $this \yii\web\View */
/* @var $content string */

use champ\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

AppAsset::register($this);
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

<div class="background-img">
	<?= Html::img('/img/background2.PNG') ?>
</div>

<div class="container-fluid">
	<div class="content">
		<div id="page-wrapper">
			<div class="breadcrumbs">
				<?= Breadcrumbs::widget([
					'homeLink' => false,
					'links'    => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				]) ?>
			</div>

            <h2>АССОЦИАЦИЯ МОТО ДЖИМХАНЫ РОССИИ</h2>
            
			<?= $content ?>
		</div>
	</div>
	<!-- /#page-wrapper -->
</div>

<?= $this->render('_modal') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
