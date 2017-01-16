<?php
/**
 * @var \common\models\Link     $link
 * @var \common\models\Contacts $info
 */

$info = $data['contacts'];
?>

<div id="content" class="contact">
    <div class="row">
        <div class="col-sm-8 col-xs-12 b-r hidden-xs">
            <div class="map">
                <div id="map" style="width:100%; height:100%"></div>
            </div>
        </div>

        <div class="col-sm-4 col-xs-12 b-r contact_text">
            <div class="addr1">
                <div class="element">
                    <div>
                        <i class="fa fa-phone"></i> <?= $info->phone ?><br>
                        <i class="fa fa-envelope-o"></i> <?= $info->email ?><br>
                        <?php foreach ($data['social'] as $link) { ?>
                            <i class="fa <?= $link->class ?>"></i> <a
                                    href="<?= $link->link ?>"><?= $link->title ?></a><br>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="addr2">
                <div class="element">
                    <div>
                        <div class="text-center"><h3>адрес</h3></div>
						<?= $info->addr ?><br>
						<?= $info->time ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://api-maps.yandex.ru/2.0/?load=package.standard,package.geoObjects&lang=ru-RU" type="text/javascript"></script>
<?php
$script = <<< JS
    ymaps.ready(init);
JS;
$this->registerJs($script);