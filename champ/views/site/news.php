<?php
/**
 * @var \yii\web\View              $this
 * @var \common\models\AssocNews $news
 */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\bootstrap\Html;

$this->registerMetaTag([
	'property'    => 'description',
	'content' => $news->previewText
]);
$this->registerMetaTag([
	'property'    => 'og:description',
	'content' => $news->previewText
]);
?>

<h2><?= $this->context->pageTitle ?></h2>

<div class="z-100">
	<div class="detail-news card-box">
		<div class="item">
			<div class="preview_text">
				<?= $news->fullText ? $news->fullText : $news->previewText ?>
			</div>
			<div class="date text-left"><?= \Yii::$app->formatter->asDate($news->datePublish, "dd.MM.Y") ?></div>
		</div>
	</div>
    <script type="text/javascript">(function() {
            if (window.pluso)if (typeof window.pluso.start == "function") return;
            if (window.ifpluso==undefined) { window.ifpluso = 1;
                var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                var h=d[g]('body')[0];
                h.appendChild(s);
            }})();</script>
    <div class="pluso" data-background="#ebebeb" data-options="small,square,line,horizontal,counter,theme=01" data-services="vkontakte,facebook,twitter,google"></div>
	<div class="text-left pt-20">
		<a href="/"><?= \Yii::t('app', 'К списку новостей') ?></a>
	</div>
</div>