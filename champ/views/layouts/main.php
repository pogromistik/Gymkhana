<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
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

<!-- ШАПКА САЙТА -->
<div class="header">
    <div class="white-menu">
        <!-- меню -->
        <div class="container">
            <nav role="navigation" class="navbar" id="nav">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <!-- Collection of nav links, forms, and other content for toggling -->
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="/">Главная</a>
                        </li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">Итем <b
                                        class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="/che/">Итем</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="/che/">Итем</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div><!-- КОНЕЦ: ШАПКА САЙТА -->

<h1 style="display: none"><?= $this->context->pageTitle ?></h1>

<div class="container">
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

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
