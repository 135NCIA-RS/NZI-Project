<?php
/**
 * Created by PhpStorm.
 * User: Przemek
 * Date: 4/17/2016
 * Time: 5:59 PM
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\components\PostsService;
use common\components\UserService;
use common\components\PhotoService;
use yii\widgets\Pjax;

/* @var $comments \common\components\Comment[] */
/* @var $userInfo \common\components\IntouchUser */
$userInfo = $this->params['userInfo'];
?>
<section class="content">

	<!-- /.col -->
	<div class="col-md-9">
		<div class="nav-tabs-custom">
			<div class="tab-content">
				<div class="active tab-pane" id="activity">
					<!-- Add post -->
					<?php Pjax::begin(); ?>
					<!-- Post -->
					<?php

					if ($comments == [])
					{
						echo Yii::t('app', 'No reported comments');
					}

					foreach ($comments as $row)
					{
						$author = $row->getAuthor();

						?>
						<div class="post">
							<div class="user-block">
								<img class="img-circle img-bordered-sm" src="<?= $author->getImageUrl() ?>"
								     alt="user image">
                                    <span class="username">
                                        <?= Html::beginForm(['site/repcomment'], 'post', ['data-pjax' => '']) ?>
	                                    <input type="hidden" name="action" value="delete">
                                         <input type="hidden" name="post_id" value="<?= $row->getId() ?>">
	                                    <!-- post_id ??? -->
                                         <button style="border: none" type="submit"
                                                 class="pull-right btn-box-tool fa fa-times"></button>
	                                    <?= Html::endForm() ?>

	                                    <?php echo Html::beginForm(['site/repcomment'], 'post', ['data-pjax' => '']) ?>
	                                    <input type="hidden" name="action" value="revoke">
                                         <input type="hidden" name="post_id" value="<?= $row->getId() ?>">
                                         <button style="border: none" type="submit"
                                                 class="pull-right btn-box-tool fa fa-minus-circle"></button>
	                                    <?= Html::endForm() ?>
	                                    <small class="text-muted pull-right"><i
				                                    class="fa fa-clock-o"></i> <?= $row->getDate() ?>

	                                    </small>
                                        <a href="#"><?= $author->getFullName() ?></a>

	                                    <!-- <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>-->

                                    </span>
							</div>
							<!-- /.user-block -->
							<p>
								<?= $row->getContent(); ?>
							</p>
						</div>
						<?php
					}
					Pjax::end();
					?>
					<!-- /.post -->
				</div>

			</div>
			<!-- /.tab-content -->
		</div>
		<!-- /.nav-tabs-custom -->
	</div>
	<!-- /.col -->
	</div>
	<!-- /.row -->

</section>


?>
