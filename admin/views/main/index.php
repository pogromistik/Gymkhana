<?php
/**
 * @var MainPhoto[]                         $sliders
 * @var MainPhoto[]                         $leftMenu
 * @var MainPhoto[]                         $bottomMenu
 * @var \yii\web\View                       $this
 * @var common\models\search\MainMenuSearch $searchModel
 * @var yii\data\ActiveDataProvider         $dataProvider
 * @var \common\models\Page                 $page
 */
use yii\helpers\Html;
use common\models\MainPhoto;
use yii\helpers\Url;
use common\models\HelpModel;
use yii\grid\GridView;
use kartik\file\FileInput;
use dosamigos\editable\Editable;
use yii\bootstrap\Collapse;

$this->title = 'Главная страница';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Collapse::widget([
	'items' => [
		[
			'label'   => 'Настройки страницы',
			'content' => $this->render('//common/_page-form', ['model' => $page])
		],
	]
]);
?>

<h3>0. Меню</h3>
<p>
	<?= Html::a('Добавить пункт меню', ['view-menu'], ['class' => 'btn btn-success']) ?>
</p>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel'  => $searchModel,
	'columns'      => [
		['class' => 'yii\grid\SerialColumn'],

		[
			'attribute' => 'type',
			'format'    => 'raw',
			'value'     => function (\common\models\MainMenu $item) {
				return \common\models\MainMenu::$typesTitle[$item->type];
			}
		],
		[
			'attribute' => 'pageId',
			'format'    => 'raw',
			'value'     => function (\common\models\MainMenu $item) {
				return $item->pageId ? $item->page->title : '';
			}
		],
		'title',
		'sort',
		[
			'format' => 'raw',
			'value'  => function (\common\models\MainMenu $item) {
				return Html::a('<span class="fa fa-edit"></span>', ['/main/view-menu', 'id' => $item->id], [
					'class' => 'btn btn-primary'
				]);
			}
		],
		[
			'format' => 'raw',
			'value'  => function (\common\models\MainMenu $item) {
				return Html::a('<span class="fa fa-remove"></span>', ['/main/delete-menu', 'id' => $item->id], [
					'class' => 'btn btn-danger',
					'data'  => [
						'confirm' => 'Уверены, что хотите удалить этот пункт?',
						'method'  => 'post',
					]
				]);
			}
		]
	],
]); ?>

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
            <table class="table picture-preview">
                <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Сортировка</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
				<?php
				foreach ($sliders as $picture) { ?>
                    <tr>
                        <td><?= Html::img(Yii::getAlias('@filesView') . $picture->fileName) ?></td>
                        <td><?= Editable::widget([
								'name'          => 'sort',
								'value'         => $picture->sort,
								'url'           => '/main/update',
								'type'          => 'text',
								'mode'          => 'inline',
								'clientOptions' => [
									'pk'        => $picture->id,
									'value'     => $picture->sort,
									'placement' => 'right',
								]
							])
							?></td>
                        <td><?= Html::a('Удалить', ['main/delete-picture', 'id' => $picture->id], [
								'data' => [
									'confirm' => 'Вы уверены, что хотите удалить это изображение?',
									'method'  => 'post',
								]
							]) ?></td>
                    </tr>
				<?php } ?>
                </tbody>
            </table>
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
				'uploadUrl'    => Url::to(['base/upload-pictures', 'type' => MainPhoto::PICTURES_RIGHT_MENU, 'modelName' => HelpModel::MODEL_MAIN_PHOTO]),
				'maxFileCount' => 10
			]
		]);
		?>

        <div class="pt-20">
            <table class="table picture-preview">
                <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Сортировка</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
				<?php
				foreach ($leftMenu as $picture) { ?>
                    <tr>
                        <td><?= Html::img(Yii::getAlias('@filesView') . $picture->fileName) ?></td>
                        <td><?= Editable::widget([
								'name'          => 'sort',
								'value'         => $picture->sort,
								'url'           => '/main/update',
								'type'          => 'text',
								'mode'          => 'inline',
								'clientOptions' => [
									'pk'        => $picture->id,
									'value'     => $picture->sort,
									'placement' => 'right',
								]
							])
							?></td>
                        <td><?= Html::a('Удалить', ['main/delete-picture', 'id' => $picture->id], [
								'data' => [
									'confirm' => 'Вы уверены, что хотите удалить это изображение?',
									'method'  => 'post',
								]
							]) ?></td>
                    </tr>
				<?php } ?>
                </tbody>
            </table>
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
            <table class="table picture-preview">
                <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Сортировка</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
				<?php
				foreach ($bottomMenu as $picture) { ?>
                    <tr>
                        <td><?= Html::img(Yii::getAlias('@filesView') . '/' . $picture->fileName) ?></td>
                        <td><?= Editable::widget([
								'name'          => 'sort',
								'value'         => $picture->sort,
								'url'           => '/main/update',
								'type'          => 'text',
								'mode'          => 'inline',
								'clientOptions' => [
									'pk'        => $picture->id,
									'value'     => $picture->sort,
									'placement' => 'right',
								]
							])
							?></td>
                        <td><?= Html::a('Удалить', ['main/delete-picture', 'id' => $picture->id], [
								'data' => [
									'confirm' => 'Вы уверены, что хотите удалить это изображение?',
									'method'  => 'post',
								]
							]) ?></td>
                    </tr>
				<?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>