<?php
/**
 * @var \common\models\City $city
 */
use yii\bootstrap\Html;

?>

    <div id="content" class="russia">
        <div class="card">
            <div id="resizable">
                <img src="/img/card.png" alt="Мотоджимхана: Карта России" title="Мотоджимхана: Карта России">
            </div>
        </div>
		
		<?php foreach ($data['cities'] as $city) { ?>
            <script>
                var table = [{
                    left: "<?= $city->left ?>%",
                    top: "<?= $city->top ?>%",
                    text: "<?= $city->title ?>"
                }];
                $.each(table, function (indx, el) {
                    var div = $("<div/>", {
                        "class": "positionDiv",
                        "css": {
                            "left": el.left,
                            "top": el.top
                        },
                        "html": '<a href="<?= $city->link ?>" target="_blank">' + el.text + '</a>'
                    }).prependTo("#resizable")
                });

                $("#resizable").resizable({handles: " e, s, se", aspectRatio: true});

            </script>
		<?php } ?>

        <div class="one_russia" id="list">
			<?php foreach ($data['cities'] as $city) { ?>
                <a href="<?= $city->link ?>" target="_blank">
                    <div class="item"><?= $city->title ?></div>
                </a>
			<?php } ?>
        </div>
    </div>

    <div class="bottom-list"><a href="#list"><img src="/img/bottom.png"></a></div>

<?php
$this->registerCSSFile('http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');
$this->registerJsFile('http://code.jquery.com/jquery-1.10.2.js', ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile('http://code.jquery.com/ui/1.10.4/jquery-ui.js', ['position' => yii\web\View::POS_HEAD]);
?>
