<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
$img = null;
$message = nl2br(Html::encode($message));
?>
<div class="site-error">

   <div class="row">
       <div class="col-sm-6 col-sm-offset-3">
           <div class="alert alert-danger">
		       <?php if (isset($exception->statusCode)) {
			       switch ($exception->statusCode) {
				       case 404:
					       $message = 'Страница не найдена. Возможно, её съел макаронный монстр или другое милое создание.<br>' .
						       'Если эта страница нужна вам - свяжитесь с <a href="https://vk.com/id19792817" target="_blank">разработчиком</a>';
					       $img = Html::img('/img/404.png');
					       break;
				       case 403:
					       $message = 'Вам запрещён доступ к запрашиваемой странице. При необходимости, обратитесь к ' .
                               'организатору вашего региона или к <a href="https://vk.com/id19792817" target="_blank">разработчику</a><br>' .
                               'P.S. картинка не несёт никакой смысловой нагрузки.';
					       $img = Html::img('/img/403.png');
					       break;
			       }
		       } ?>
		       <?= $message ?>
           </div>
	
	       <?php if ($img) { ?>
               <div class="img">
			       <?= $img ?>
               </div>
	       <?php } ?>
       </div>
   </div>

</div>
