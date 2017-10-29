<?php
/**
 * @var array $info
 */
use yii\bootstrap\Html;

?>

<div id="content" class="container small-height">
    <ul>
        <li>
            <a href="//gymkhana-cup.ru/" target="_blank">GYMKHANA CUP</a>
        </li>
		<?php foreach ($data['map'] as $layout => $info) { ?>
            <li>
				<?= Html::a($info['title'], $info['url']) ?>
				<?php if (isset($info['children'])) { ?>
                    <ul>
						<?php foreach ($info['children'] as $child) { ?>
                            <li><?= Html::a($child['title'], $child['url']) ?></li>
						<?php } ?>
                    </ul>
				<?php } ?>
            </li>
		<?php } ?>
    </ul>
</div>
