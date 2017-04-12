<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Client;

/**
 * @var yii\web\View                     $this
 * @var \common\models\User              $user
 * @var \dektrium\rbac\models\Assignment $assignment
 */

$this->title = Yii::t('user', 'Create a user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-body">
				<?= $this->render('_user-form', ['user' => $user, 'assignment' => $assignment]) ?>
			</div>
		</div>
	</div>
</div>
