<?php
/* @var $this \yii\web\View */
/* @var $content string */
/* @var $userInfo \common\components\IntouchUser */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
$userInfo = $this->params['userInfo'];
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
	<body class="hold-transition skin-red sidebar-mini">
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
							
							<!-- User Account: style can be found in dropdown.less -->
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									
									<!-- <?php echo Html::img(\common\components\PhotoService::getProfilePhoto(0),
											['class' => "user-image"]) ?>-->
									<i class="fa fa-sign-in"></i>
									<span class="hidden-xs"><?= Yii::t('app', 'Account'); ?></span>
								</a>
								<ul class="dropdown-menu">
									<!-- User image -->
									
									<li class="user-header">
										
										<?= Html::img($userInfo->getImageUrl(),
												['class' => 'img-circle', 'alt' => 'User Image']) ?>
										<p style='color:black; font-weight:bold'><?= $userInfo->getFullName() ?></p>
										<p>
											<?= Yii::t('app', 'You\'re InTouch now.'); ?>
										</p>
									
									</li>
									<!-- Menu Body -->
									<li class="user-body">
									</li>
									<!-- Menu Footer-->
									<li class="user-footer">
										<div class="pull-left">
											<div style="padding: 8px; color: red;"> <?= Yii::t('app',
														'Admin Mode') ?></div>
										</div>
										<div class="pull-right">
											<a href="<?= Url::to(['/logout']) ?>" data-method="post"
											   class="btn btn-default btn-flat"><?= Yii::t('app', 'Log out'); ?></a>
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
							<?= Html::img($userInfo->getImageUrl(),
									['class' => 'img-circle', 'alt' => 'User Image']) ?>
						</div>
						<div class="pull-left info">
							<p><?= $userInfo->getFullName() ?></p>
							<a href="#"><i class="fa fa-circle text-success"></i> <?= Yii::t('app', 'Online'); ?></a>
						</div>
					
					</div>
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">
						<li class="header"><?= Yii::t('app', 'MAIN NAVIGATION'); ?></li>
						<li>
							<a href="/rep">
								<i class="fa fa-bicycle"></i> <span><?= Yii::t('app', 'Reported Posts') ?></span>
							</a>
						</li>
						<li>
							<a href="/reportedComment">
								<i class="fa fa-comments"></i> <span><?= Yii::t('app', 'Reported Comments') ?></span>
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
									echo '<li><a href="/action/lang?lang=' . $key . '"><i class="flag-icon flag-icon-' .
									     $key . '"></i>' . ' | ' . $lang . '</a></li>';
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
