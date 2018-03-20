<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>resizable demo</title>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

    <style>
        body {
            background: #333
        }

        #resizable {
            width: 100%;
            height: 100%;
            margin: 0% auto;
        }

        #resizable img {
            width: 100%;
            height: 100%;
        }

        .positionDiv {
            width: 6px;
            height: 6px;
            background: red;
            position: absolute;
            z-index: 100;
            color: #000000;
            background: red;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }

        .positionDiv p {
            display: none;
            color: #ffffff;
            padding: 0;
        }

        .positionDiv:hover p {
            display: block;
        }

        .circle {
            position: absolute;
            width: 6px;
            height: 6px;
            /*background: #32e11a;*/
            background: red;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }
    </style>
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
</head>


<?php
$mass[] = "москва, 15, 30.76";
$mass[] = "2, 12.08, 31.5";
$mass[] = "3, 11.3, 35.5";
$mass[] = "4, 14.05, 33.9";
$mass[] = "5, 9.5, 45.1";
$mass[] = "6, 12.35, 44.7";
$mass[] = "7, 13.2, 44.3";
$mass[] = "8, 13.86, 45.3";
$mass[] = "9, 13.72, 49.58";
$mass[] = "10, 11.55, 52.7";
$mass[] = "11, 10.42, 53.7";

$mass[] = "12, 6.5, 61.7";
$mass[] = "13, 3.8, 63.8";
$mass[] = "14, 2.98, 68.6";
$mass[] = "15, 5.95, 70.5";
$mass[] = "16, 5.6, 71.4";
$mass[] = "27, 20.2, 56.7";
$mass[] = "18, 27.66, 64.1";
$mass[] = "19, 26.88, 68.7";
$mass[] = "20, 22.3, 74.2";
$mass[] = "21, 35.74, 60.2";
$mass[] = "22, 44, 77.2";
$mass[] = "23, 42, 80.7";
$mass[] = "24, 41.85, 85.1";
?>

<body>
<div id="resizable">
    <img src="card.png" alt="">
</div>

<?php
foreach ($mass as $m) {
	$res = explode(', ', $m);
	?>
    <script>
        var table = [{
            left: "<?=$res[1]?>%",
            top: "<?=$res[2]?>%",
            text: "<?=$res[0]?>"
        }];
        $.each(table, function (indx, el) {
            var div = $("<div/>", {
                "class": "positionDiv",
                "css": {
                    "left": el.left,
                    "top": el.top
                },
                "html": '<p>' + el.text + '</p>'
            }).prependTo("#resizable")
        });

        $("#resizable").resizable({handles: " e, s, se", aspectRatio: true});
        $("#resizable").click(function (ev) {
            var div = $("<div/>", {
                "class": "circle",
                "title": "circle"
            })
            div.prependTo(this).position({
                my: "left top",
                of: ev,
                offset: "3 -3",
                collision: "fit"
            });
            var pos = div.position(),
                h = $(this).height(),
                w = $(this).width(),
                left = Math.round(pos.left * 100 / w) + "%",
                top = Math.round(pos.top * 100 / h) + "%";

            div.css({
                "left": left,
                "top": top
            })
            div.tooltip({content: left + top});
        })
    </script>
	<?php
}
?>

</body>
</html>