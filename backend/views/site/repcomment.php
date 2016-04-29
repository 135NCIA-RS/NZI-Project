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
					foreach ($posts as $row)
					{
						?>
						<div class="post">
							<div class="user-block">
								<img class="img-circle img-bordered-sm" src="<?php echo $row['photo']; ?>"
								     alt="user image">
                                    <span class="username">
                                        <a href="#"><?php echo($row['name'] . " " . $row['surname']); ?></a>


	                                    <!--                                        <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>-->

	                                    <?php echo Html::beginForm(['site/repcomment'], 'post', ['data-pjax' => '']) ?>
	                                    <input type="hidden" name="action" value="delete">
                                         <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                                         <button style="..." type="submit"
                                                 class="pull-right fa fa-times"></button>
	                                    <?= Html::endForm() ?>

	                                    <?php echo Html::beginForm(['site/report'], 'post', ['data-pjax' => '']) ?>
	                                    <input type="hidden" name="action" value="revoke">
                                         <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                                         <button style="..." type="submit"
                                                 class="pull-right fa fa-minus-circle"></button>
	                                    <?= Html::endForm() ?>



                                    </span>
                                    <span class="description"><?php if ($row['post_visibility'] == "visible")
	                                    {
		                                    echo Yii::t('app', 'Post public');
	                                    }
	                                    else
	                                    {
		                                    echo Yii::t('app', 'Post hidden');
	                                    }
	                                    ?> - <?php echo($row['post_date']); ?></span>
							</div>
							<!-- /.user-block -->
							<p>
								<?php
								$attachments = $row['attachments'];
								if ($row['post_type'] == "text")
								{
									echo $row['post_text'];
								}
								else if ($row['post_type'] == "gallery")
								{
								echo $row['post_text'] . "<br>";
								?>
							<div class="row margin-bottom">
								<div class="col-sm-6">
									<img class="img-responsive"
									     src="../../dist/content/attachments/<?php echo $attachments[0]['file']; ?>"
									     alt="Photo">
								</div>
								<!-- /.col -->
								<div class="col-sm-6">
									<div class="row">
										<div class="col-sm-6">
											<img class="img-responsive"
											     src="../../dist/content/attachments/<?php echo $attachments[1]['file']; ?>"
											     alt="Photo">
											<br>
											<img class="img-responsive"
											     src="../../dist/content/attachments/<?php echo $attachments[2]['file']; ?>"
											     alt="Photo">
										</div>
										<!-- /.col -->
										<div class="col-sm-6">
											<img class="img-responsive"
											     src="../../dist/content/attachments/<?php echo $attachments[3]['file']; ?>"
											     alt="Photo">
											<br>
											<?php if (isset($attachments[4]['file']))
											{
												?><img class="img-responsive"
												       src="../../dist/content/attachments/<?php echo $attachments[1]['file']; ?>"
												       alt="Photo"> <?php } ?>
										</div>
										<!-- /.col -->
									</div>
									<!-- /.row -->
								</div>
								<!-- /.col -->
							</div>
							<?php
							}
							?>
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
