<?php
use yii\helpers\Url;

?>

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
                        <li>
                            <a href="<?= Url::to(['site/documents']) ?>">Документы</a>
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