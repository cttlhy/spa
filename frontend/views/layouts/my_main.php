<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\SbAppAsset;
use yii\helpers\Html;
use common\widgets\Alert;
use yii\widgets\Breadcrumbs;

SbAppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Super ADS</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">SuperADS Channel Admin</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Welcome <?= Yii::$app->user->identity->username?> <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
<!--                    <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                    </li>
                    <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                    </li>
                    <li class="divider"></li>
-->
                    <li>
                        <?php
                        echo Html::beginForm(['/site/logout'], 'post', ['id' => 'logout-form']);
                        echo Html::endForm();
                        ?>
                        <?php
                        echo Html::beginTag("a", ['href' => '#', 'onclick' => 'document.getElementById("logout-form").submit();']);
                        echo Html::beginTag("i", ['class' => 'fa fa-sign-out fa-fw']);
                        echo Html::endTag("i");
                        echo 'Logout (' . Yii::$app->user->identity->username . ')';
                        //echo " Logout";
                        echo Html::endTag("a");
                        ?>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="../camp-log/index"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-files-o fa-fw"></i> Offers<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <?= Html::a('All Offers', ['camp-log/alloffers'],['data-menu'=>"alloffers"]) ?>
                            </li>

                            <li>
                                <?= Html::a('My Approved Offers', ['camp-log/myoffers'],['data-menu'=>"myoffers"]) ?>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Reports<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <?php //  Html::a('Hourly Report', ['my-report/hourly'],['data-menu'=>"hourly"]) ?>
                            </li>
                            <li>
                                <?php //  Html::a('Daily Report', ['my-report/daily'],['data-menu'=>"daily"]) ?>
                            </li>
                            <li>
                                <?=  Html::a('Offers Report', ['my-report/offers'],['data-menu'=>"offers"]) ?>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-wrench fa-fw"></i> Support<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/support/api" data-menu="channel-api">API</a>
                            </li>

                        </ul>
                        <!-- /.nav-second-level -->
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-user fa-fw"></i> Account<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/account/payment" data-menu="channel-payment">Payment</a>
                            </li>
                            <li>
                                <a href="#">Setting</a>
                            </li>

                        </ul>
                        <!-- /.nav-second-level -->
                    </li>

                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>
    <div id="page-wrapper">
        <div class="row">
            <?= $content ?>
        </div>

    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
