<?php
/**
 * @var MainPhoto[]                     $sliders
 * @var MainPhoto[]                     $leftMenu
 * @var MainPhoto[]                     $bottomMenu
 * @var \yii\web\View                   $this
 * @var common\models\search\LinkSearch $searchModel
 * @var yii\data\ActiveDataProvider     $dataProvider
 */
use yii\helpers\Html;
use common\models\MainPhoto;
use yii\helpers\Url;
use common\models\HelpModel;
use yii\grid\GridView;
use kartik\file\FileInput;

$this->params['breadcrumbs'][] = $this->title;
$this->title = 'Главная страница'
?>

<div class="row">
	<div class="col-xs-12">
		<h3>1. Слайдер</h3>
		<?= FileInput::widget([
			'name'          => 'attachment_48[]',
			'options'       => [
				'multiple' => true,
				'accept'   => 'image/*',
				'id'       => 'slider-input',
				'class'    => 'file-upload'
			],
			'pluginOptions' => [
				'uploadUrl'    => Url::to(['base/upload-pictures', 'type' => MainPhoto::PICTURES_SLIDER, 'modelName' => HelpModel::MODEL_MAIN_PHOTO]),
				'maxFileCount' => 10
			]
		]);
		?>
		<div class="pt-20">
			<?php
			foreach ($sliders as $picture) { ?>
				<div class="row">
					<div class="col-md-2 col-sm-4 cul-xs-6">
						<?= Html::img(Yii::getAlias('@picturesView') . '/' . MainPhoto::$filePath[$picture->type] . '/' . $picture->fileName) ?>
					</div>
					<div class="col-md-8 col-sm-8 cul-xs-6">
						<?= Html::a('Удалить', ['main/delete-picture', 'id' => $picture->id], [
							'data' => [
								'confirm' => 'Вы уверены, что хотите удалить это изображение?',
								'method'  => 'post',
							]
						]) ?>
					</div>
				</div>
			<?php }
			?>
		</div>
	</div>

	<div class="col-xs-12">
		<h3>2. Левое меню</h3>
		<?= FileInput::widget([
			'name'          => 'attachment_48[]',
			'options'       => [
				'multiple' => true,
				'accept'   => 'image/*',
				'id'       => 'left-menu-input',
				'class'    => 'file-upload'
			],
			'pluginOptions' => [
				'uploadUrl'    => Url::to(['base/upload-pictures', 'type' => MainPhoto::PICTURES_LEFT_MENU, 'modelName' => HelpModel::MODEL_MAIN_PHOTO]),
				'maxFileCount' => 10
			]
		]);
		?>

		<div class="pt-20">
			<?php
			foreach ($leftMenu as $picture) { ?>
				<div class="row">
					<div class="col-md-2 col-sm-4 cul-xs-6">
						<?= Html::img(Yii::getAlias('@picturesView') . '/' . MainPhoto::$filePath[$picture->type] . '/' . $picture->fileName) ?>
					</div>
					<div class="col-md-8 col-sm-8 cul-xs-6">
						<?= Html::a('Удалить', ['main/delete-picture', 'id' => $picture->id], [
							'data' => [
								'confirm' => 'Вы уверены, что хотите удалить это изображение?',
								'method'  => 'post',
							]
						]) ?>
					</div>
				</div>
			<?php }
			?>
		</div>
	</div>

	<div class="col-xs-12">
		<h3>3. Нижнее меню</h3>
		<?= FileInput::widget([
			'name'          => 'attachment_48[]',
			'options'       => [
				'multiple' => true,
				'accept'   => 'image/*',
				'id'       => 'bottom-menu-input',
				'class'    => 'file-upload'
			],
			'pluginOptions' => [
				'uploadUrl'    => Url::to(['base/upload-pictures', 'type' => MainPhoto::PICTURES_BOTTOM_MENU, 'modelName' => HelpModel::MODEL_MAIN_PHOTO]),
				'maxFileCount' => 10
			]
		]);
		?>
		<div class="pt-20">
			<?php
			foreach ($bottomMenu as $picture) { ?>
				<div class="row">
					<div class="col-md-2 col-sm-4 cul-xs-6">
						<?= Html::img(Yii::getAlias('@picturesView') . '/' . MainPhoto::$filePath[$picture->type] . '/' . $picture->fileName) ?>
					</div>
					<div class="col-md-8 col-sm-8 cul-xs-6">
						<?= Html::a('Удалить', ['main/delete-picture', 'id' => $picture->id], [
							'data' => [
								'confirm' => 'Вы уверены, что хотите удалить это изображение?',
								'method'  => 'post',
							]
						]) ?>
					</div>
				</div>
			<?php }
			?>
		</div>
	</div>
</div>


	<h3>Ссылки на соцсети</h3>
	<p>
		<?= Html::a('Добавить ссылку', ['create-link'], ['class' => 'btn btn-success']) ?>
	</p>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel'  => $searchModel,
	'columns'      => [
		['class' => 'yii\grid\SerialColumn'],

		[
			'attribute' => 'title',
			'format'    => 'raw',
			'value'     => function (\common\models\Link $link) {
				return Html::a($link->title, ['update-link', 'id' => $link->id]);
			}
		],
		'link',
		'class',
	],
]); ?>