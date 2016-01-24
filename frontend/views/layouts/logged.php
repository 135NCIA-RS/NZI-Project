<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <?php $this->beginBody() ?>
        <div class="wrapper">


            <header class="main-header">
                <!-- Logo -->
                <a href="/" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>I</b>T</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>In</b>Touch</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Notifications: style can be found in dropdown.less -->
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell-o"></i>
                                    <span class="label label-warning"><?= $this->params['notification_count'] ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header"><?= Yii::t('app','You have'); ?> <?= $this->params['notification_count'] ?> <?= Yii::t('app','notification(s)'); ?></li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            <li>
                                                <?php
                                                if ($this->params['notification_count'] > 0)
                                                {
                                                    ?>
                                                    <a href="/notifications">
                                                        <i class="fa fa-users text-aqua"></i> <?= Yii::t('app','New friend request'); ?>
                                                    </a>
                                                    <?php
                                                }
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="/notifications"><?= Yii::t('app','View all'); ?></a></li>
                                </ul>
                            </li>
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                                    <!-- <?php echo Html::img('@web/dist/img/guest.png', ['class' => "user-image"]) ?>-->
                                    <i class="fa fa-sign-in"></i>
                                    <span class="hidden-xs"><?= Yii::t('app','Account'); ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->

                                    <li class="user-header">

                                        <?= Html::img($this->params['userProfilePhoto'], ['class' => 'img-circle', 'alt' => 'User Image']) ?>
                                        <p style='color:black; font-weight:bold'><?= $this->params['userInfo']['user_name'] . ' ' . $this->params['userInfo']['user_surname'] ?></p>
                                        <p>
                                            <?= Yii::t('app','You\'re InTouch now.'); ?>
                                        </p>

                                    </li>
                                    <!-- Menu Body -->
                                    <li class="user-body">

                                        <div class="col-xs-4 text-center">
                                            <a href="/myFriends"><?= Yii::t('app','Friends'); ?></a>
                                        </div>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="/profile" class="btn btn-default btn-flat"><?= Yii::t('app','Profile'); ?></a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="<?= Url::to(['/logout']) ?>" data-method="post" class="btn btn-default btn-flat"><?= Yii::t('app','Log out'); ?></a>                                           
                                        </div>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <?= Html::img($this->params['userProfilePhoto'], ['class' => 'img-circle', 'alt' => 'User Image']) ?>
                        </div>
                        <div class="pull-left info">
                            <p><?= $this->params['userInfo']['user_name'] . ' ' . $this->params['userInfo']['user_surname'] ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> <?= Yii::t('app','Online'); ?></a>
                        </div>

                    </div>
                    <!-- search form -->
                    <form action="/search" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder=<?= Yii::t('app','Search...'); ?>>
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header"><?= Yii::t('app','MAIN NAVIGATION'); ?></li>
                        <li>
                            <a href="/profile">
                                <i class="fa fa-user"></i> <span><?= Yii::t('app', 'Profile') ?></span> 
                            </a>
                        </li>
                        <li>
                            <a href="/myFriends">
                                <i class="fa fa-users"></i> <span><?= Yii::t('app', 'Friends') ?></span> 
                            </a>
                        </li>
                        <li class="header"><?= Yii::t('app', 'ACTIONS') ?></li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-language"></i> <span><?= Yii::t('app', 'Language') . '&nbsp;' ?>
                                </span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <?php
                                foreach (\Yii::$app->params['languages'] as $key => $lang)
                                {
                                    echo '<li><a href="/action/lang?lang=' . $key . '"><i class="flag-icon flag-icon-' . $key . '"></i>' . ' | ' . $lang . '</a></li>';
                                }
                                ?>
                            </ul>
                        </li>

                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <!-- Main content -->
                <section class="content">
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </section>
            </div>


            <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
