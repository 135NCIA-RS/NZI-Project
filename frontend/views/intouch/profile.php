<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\components\PostsService;
use common\components\UserService;
use common\components\PhotoService;
use common\components\ScoreService;
use common\components\ScoreElemEnum;
use common\components\ScoreTypeEnum;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $userinfo \common\components\IntouchUser */
/* @var $posts \common\components\Post[] */
/* @var $loggedUser \common\components\IntouchUser */
/* @var $timeline \common\components\UserEvent[] */

?>
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<!-- Profile Image -->
				<div class="box box-primary">
					<div class="box-body box-profile">
						<?= Html::img($userinfo->getImageUrl(),
								['class' => 'profile-user-img img-responsive img-circle',
								 'alt' => 'User profile image']) ?>
						<h3 class="profile-username text-center"><?= $userinfo->getFullName() ?></h3>
						<p class="text-muted text-center"><?= Yii::t('app', 'InTouch User'); ?></p>
						<ul class="list-group list-group-unbordered">
							<li class="list-group-item">
								<b><?= Yii::t('app', 'Followers'); ?></b> <a class="pull-right"><?= $followers ?></a>
							</li>
							<li class="list-group-item">
								<b><?= Yii::t('app', 'Following'); ?></b> <a class="pull-right"><?= $following ?></a>
							</li>
							<li class="list-group-item">
								<b><?= Yii::t('app', 'Friends'); ?></b> <a class="pull-right"><?= $friends ?></a>
							</li>
						</ul>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
				<!-- About Me Box -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?= Yii::t('app', 'About me'); ?></h3>
						<span class="pull-right"><a href="profile/aboutedit"><i class="fa fa-cog"></i></a></span>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<strong><i class="fa fa-book margin-r-5"></i> <?= Yii::t('app', 'Education'); ?></strong>
						<p class="text-muted">
							<?= $userinfo->getEducation() ?>
						</p>
						<hr>
						<strong><i class="fa fa-map-marker margin-r-5"></i> <?= Yii::t('app', 'Location'); ?></strong>
						<p class="text-muted"><?= $userinfo->getCity() ?></p>
						<hr>
						
						<strong><i class="fa fa-birthday-cake margin-r-5"></i> <?= Yii::t('app', 'Birthday'); ?>
						</strong>
						
						<p>
							<?= $userinfo->getBirthDate() ?>
						</p>
						
						<hr>
						
						<strong><i class="fa fa-file-text-o margin-r-5"></i> <?= Yii::t('app', 'Miscellaneous'); ?>
						</strong>
						
						<p><?= $userinfo->getAbout() ?></p>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
			<div class="col-md-9">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#activity" data-toggle="tab"><?= Yii::t('app', 'Activity'); ?></a>
						</li>
						<li><a href="#timeline" data-toggle="tab"><?= Yii::t('app', 'Timeline'); ?></a></li>
						<li><a href="#settings" data-toggle="tab"><?= Yii::t('app', 'Settings'); ?></a></li>
					</ul>
					
					
					<div class="tab-content">
						<div class="active tab-pane" id="activity">
							<!-- Add post -->
							<?php Pjax::begin(); ?>
							<?php echo Html::beginForm(['intouch/profile'], 'post', ['data-pjax' => '']) ?>
							<input class="form-control input-sm send-form-input" row="3" type="text" placeholder="Post"
							       name="inputText">
							<input type="hidden" name="type" value="newpost">
							<!-- Add picture-->
							<div class="btn-file btn btn-default fa fa-t link-black text-sm" style="margin-top: 5px;">
								<i class="fa fa-paperclip "></i><i style="font: inherit"><?= Yii::t('app', ' Attach Image') ?></i>
								<input type="file" name="kawaiiPicture[]" multiple>
							</div>
							<!-- /Add picture-->
							<button style="width:20%; margin-top:5px;" type="submit"
							        class="btn btn-danger pull-right btn-primary btn-sm"><?= Yii::t('app',
										'Publish'); ?></button>
							<br>
							<hr>
							<?= Html::endForm() ?>
							<!-- /Add post-->
							<!-- Post -->
							<?php
							foreach ($posts as $row)
							{
								$author = $row->getAuthor();
								$comments = $row->getComments();
								?>
								<div class="post">
									<div class="user-block">
										<img class="img-circle img-bordered-sm" src="<?= $author->getImageUrl() ?>"
										     alt="user image">
                                    <span class="username">
                                        <a href="user/<?= $author->getUsername() ?>"><?= $author->getFullName() ?></a>
	                                    <?php echo Html::beginForm(['intouch/profile'], 'post') ?>
	                                    <input type="hidden" name="post_id" value="<?= $row->getId() ?>">
                                        <input class="" type="hidden" name="type" value="delete_post"
                                               id="delete_post-form">
                                        <button style="border-style: none; margin-top: 2px" type="submit"
                                                class="pull-right btn-box-tool fa fa-times"></button>
	                                    <?= Html::endForm() ?>

                                        <?php
                                        $postOwner=$author->getId();
                                        if($userinfo->getId() == $postOwner || Yii::$app->user->can('admin'))
                                            echo '<button type="button"
                                                                    onclick="window.location.href=\'/post/edit/'.$row->getId().' \'"
															        class="btn btn-box-tool dropdown-toggle"
															        data-toggle="dropdown">
																<i class="fa fa-wrench"></i></button>';
                                        ?>

	                                    <!--                                        <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>-->

                                    </span>
                                    <span class="description"><?php
	                                    if ($row->checkVisibility(\common\components\EVisibility::visible()))
	                                    {
		                                    echo Yii::t('app', 'Post public');
	                                    }
	                                    else
	                                    {
		                                    echo Yii::t('app', 'Post hidden');
	                                    }
	                                    ?> - <?= $row->getDate() ?></span>
									</div>
									<!-- /.user-block -->
									<p>
										<?php
										$attachment = $row->getAttachments();
                                                                                //die(var_dump($attachment));
										/* @var $attachment common\components\PostAttachment */
										echo $row->getContent();
										if ($attachment != null)
										{
                                                                                    $attachment = $attachment->getFile();
										echo "<br>";
										?>
									<div class="row margin-bottom">
                                                                                <?php foreach($attachment as $att) { ?>
										<div class="col-sm-6">
											<img class="img-responsive"
                                                                                             src="<?= $att ?>"
											     alt="Photo">
										</div>
                                                                                <?php } ?>
										<!-- /.col -->
									</div>
									<?php
									}
									?>
									</p>
									<ul class="list-inline">
										<li><a href="#" class="link-black text-sm"><i
														class="fa fa-share margin-r-5"></i> <?= Yii::t('app',
														'Share'); ?></a></li>
										<li>
											<?php echo Html::beginForm(['intouch/profile'], 'post') ?>
											<input type="hidden" name="post_id" value="<?= $row->getId() ?>">
											<input type="hidden" name="score_elem" value="post">
											<input type="hidden" name="user_id" value="<?= $loggedUser->getId() ?>">
											<input class="" type="hidden" name="type" value="like" id="like-form">
                                            <span class="link-black text-sm">
                                                <i class="fa fa-thumbs-o-up"></i>
                                                <input type="submit" class="fa fa-t humbs-o-up link-black text-sm"
                                                       style="background: none; border: none"
                                                       value="<?=
                                                       Yii::t('app', 'Like') .
                                                       " (" .
                                                       $row->countScoresByType(\common\components\EScoreType::like()) .
                                                       ")"
                                                       ?> ">
                                            </span>
											<?= Html::endForm() ?>
										</li>
										<li class="pull-right"><a href="#" class="link-black text-sm"><i
														class="fa fa-comments-o margin-r-5"></i> <?= Yii::t('app',
														'Comments'); ?> (<?php echo count($comments); ?>)</a>
										</li>
										<li class="pull-right">
											<?php echo Html::beginForm(['intouch/profile'], 'post') ?>
											<input type="hidden" name="post_id" value="<?= $row->getId() ?>">
											<input type="hidden" name="score_elem" value="post">
											<input type="hidden" name="user_id" value="<?= $loggedUser->getId() ?>">
											<input class="" type="hidden" name="type" value="report" id="like-form">
                                            <span class="link-black text-sm">
                                                <i class="fa fa-exclamation"></i>
                                                <input type="submit" class="link-black text-sm"
                                                       style="background: none; border: none;"
                                                       value="<?= Yii::t('app', 'Report'); ?>">
                                            </span>
											
											<?= Html::endForm() ?>
										
										</li>
									</ul>
									<?php echo Html::beginForm(['intouch/profile'], 'post', ['data-pjax' => '']) ?>
									<input class="form-control input-sm send-form-input" type="text"
									       placeholder="<?= Yii::t('app', 'Type a comment'); ?>" name="inputText">
									<input type="hidden" name="type" value="newcomment">
									<input type="hidden" name="post_id" value="<?= $row->getId() ?>">
									<button style="width:20%; margin-top:5px;" type="submit"
									        class="btn btn-danger btn-block btn-sm hidden"></button>
									<?= Html::endForm() ?>
									<div class="direct-chat-msg" style="margin-top: 10px;">
										<div class="direct-chat-info clearfix">
										</div>
										<!-- /.direct-chat-info -->
										<?php
										foreach ($comments as $comment)
										{
											?>
											<div style="background-color: #EDF5F7; padding: 10px 10px 1px 10px; border-radius: 10px; margin-left: 30px; margin-bottom:5px;">
												<img class="direct-chat-img"
												     src="<?php echo $comment->getAuthor()->getImageUrl(); ?>"
												     alt="message user image" style="margin-right: 10px;">
												<!-- /.direct-chat-img -->
												<p class="message">
													<a href="#" class="name">
														<small class="text-muted pull-right">
															<!--button to delete comment -->
															<?php echo Html::beginForm(['intouch/profile'], 'post') ?>
															<input type="hidden" name="comment_id"
															       value="<?= $comment->getId() ?>">
															<input class="" type="hidden" name="type"
															       value="delete_comment" id="delete_comment-form">
															<button style="border-style: none; margin-top: 2px"
															        type="submit"
															        class="pull-right btn btn-box-tool fa fa-times"></button>
															<?= Html::endForm() ?>
															<i class="fa fa-clock-o"></i> <?php echo $comment->getDate() ?>
															<?php

                                                            $nothing = $comment->getAuthor();
                                                            $commentOwner= $nothing->getId();
                                                            if($userinfo->getId() == $commentOwner || Yii::$app->user->can('admin'))
                                                            echo '<button type="button"
                                                                    onclick="window.location.href=\'/post/commentEdit/'.$comment->getId().'\'"
															        class="btn btn-box-tool dropdown-toggle"
															        data-toggle="dropdown">
																<i class="fa fa-wrench"></i></button>';
                                                            ?>



															<!--                                                            <button type="button" class="btn btn-box-tool"-->
															<!--                                                                    data-widget="remove">-->
															<!--                                                                <i class="fa fa-times"></i></button>-->
														
														</small>
														<?= $comment->getAuthor()->getFullName() ?><br>
													</a>
													<?= $comment->getContent() ?>
												</p>
											</div>
										<?php } ?>
										<!-- /.direct-chat-text -->
									</div>
								</div>
								<?php
							}
							Pjax::end();
							?>
							<!-- /.post -->
						</div>
						<div class="tab-pane" id="timeline">
							<!-- The timeline -->
							<ul class="timeline timeline-inverse">
								<!-- timeline time label -->
								<li class="time-label">
                                <span class="bg-red">
                                    <?= (new DateTime())->format("d-m-Y") ?>
                                </span>
								</li>
								<!-- /.timeline-label -->
								<?php
								$lastDate = (new DateTime())->format("Y-m-d");
								foreach ($timeline as $event)
								{
									$dateChanged = $event->getEventDate("Y-m-d") != $lastDate;
									$userConnected = $event->getEventOwner();
									$userConnectedDefault = true;
									$content = "Unknown Event Type";
									$icon = "fa-exclamation-triangle";
									$color = "bg-red";
									switch ($event->getEventType())
									{
										case \common\components\EEvent::ACCOUNT_CREATE():
											$icon = "fa-user-plus";
											$color = "bg-green";
											$content = "<a href='/user/" . $userConnected->getUsername() . "'>" .
											           Yii::t('app', 'You') . "</a>&nbsp
								                " . Yii::t('app', 'have created an account');
											break;
										case \common\components\EEvent::ACCOUNT_INFO_CHANGED():
											$icon = "fa-user";
											$color = "bg-aqua";
											$content = "<a href='/user/ " . $userConnected->getUsername() . "'>" .
											           Yii::t('app', 'You') . "</a>&nbsp"
											           . Yii::t('app', 'have changed your account details');
											break;
										case \common\components\EEvent::ACCOUNT_PASSWORD_CHANGED():
											$icon = "fa-user-secret";
											$color = "bg-yellow";
											$content = "<a href='/user/" . $userConnected->getUsername() . "'>" .
											           Yii::t('app', 'You') . "</a>&nbsp"
											           . Yii::t('app', 'have changed your password');
											break;
										case \common\components\EEvent::ACCOUNT_PASSWORD_RESET():
											$icon = "fa-user-md";
											$color = "bg-red";
											$content = "<a href='/user/" . $userConnected->getUsername() . "'>" .
											           Yii::t('app', 'You') . "</a>&nbsp"
											           . Yii::t('app', 'have reset your password');
											break;
										case \common\components\EEvent::COMMENT_CREATE():
											$icon = "fa-comment-o";
											$color = "bg-fuchsia";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have commented on") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName() . " </a>" .
													           Yii::t('app', 'post');
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has commented on your post");
												}
											}
											else
											{
												$content = "<a href='/user/" . $event->getEventOwner()->getUsername() .
												           "'>" . Yii::t('app', 'You') . "</a>&nbsp"
												           . Yii::t('app', 'have commented on your post');
											}
											break;
										case \common\components\EEvent::COMMENT_DELETE():
											$icon = "fa-comment";
											$color = "bg-red";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/users/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have deleted a comment from") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName() . " </a>" .
													           Yii::t('app', 'post');
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has deleted a comment from your post");
												}
											}
											else
											{
												$content = "<a href='/user/" . $event->getEventOwner()->getUsername() .
												           "'>" . Yii::t('app', 'You') . "</a>&nbsp"
												           . Yii::t('app', 'have deleted a comment from your profile');
											}
											break;
										case \common\components\EEvent::COMMENT_EDIT():
											$icon = "fa-commenting-o";
											$color = "bg-yellow";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have edited a comment from") .
													           "<a href='/users/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName() . " </a>" .
													           Yii::t('app', 'post');
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has commented on your post");
												}
											}
											else
											{
												$content = "<a href='/user/" . $event->getEventOwner()->getUsername() .
												           "'>" . Yii::t('app', 'You') . "</a>&nbsp"
												           . Yii::t('app', 'have edited a comment from your profile');
											}
											break;
										case \common\components\EEvent::POST_CREATE():
											$icon = "fa-pencil";
											$color = "bg-maroon";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content =
															"<a href='/user/" .
															$event->getEventOwner()->getUsername() . "'>" .
															Yii::t('app', 'You') . "</a>&nbsp"
															. Yii::t('app', "have added a post to") . " " .
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . Yii::t('app', '\'s profile');
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has added a post to your profile");
												}
											}
											else
											{
												$content = "<a href='/user/" . $event->getEventOwner()->getUsername() .
												           "'>" . Yii::t('app', 'You') . "</a>&nbsp"
												           . Yii::t('app', 'have added a post from your profile');
											}
											break;
										case \common\components\EEvent::POST_DELETE():
											$icon = "fa-pencil";
											$color = "bg-lime";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have deleted a post from") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName()  .
													           Yii::t('app', '\'s profile') . " </a>";
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has deleted his post from your profile");
												}
											}
											else
											{
												$content = "<a href='/user/" . $event->getEventOwner()->getUsername() .
												           "'>" . Yii::t('app', 'You') . "</a>&nbsp"
												           . Yii::t('app', 'have deleted a post from your profile');
											}
											break;
										case \common\components\EEvent::POST_EDIT():
											$icon = "fa-pencil";
											$color = "bg-maroon";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have edited your post from") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName()  .
													           Yii::t('app', '\'s profile') . " </a>";
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has edited his post at your profile");
												}
											}
											else
											{
												$content = "<a href='/user/" . $event->getEventOwner()->getUsername() .
												           "'>" . Yii::t('app', 'You') . "</a>&nbsp"
												           . Yii::t('app', 'have edited a post from your profile');
											}
											break;
										case \common\components\EEvent::POST_LIKED():
											$icon = "fa-pencil";
											$color = "bg-teal";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', 'have liked a post from') .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName()  .
													           Yii::t('app', '\'s profile') . " </a>";
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has liked a post from your profile");
												}
											}
											else
											{
												$content = "<a href='/user/" . $event->getEventOwner()->getUsername() .
												           "'>" . Yii::t('app', 'You') . "</a>&nbsp"
												           . Yii::t('app', 'have liked a post from your profile');
											}
											break;
										case \common\components\EEvent::POST_UNLIKED():
											$icon = "fa-pencil";
											$color = "bg-navy";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have unliked a post from") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName()  .
													           Yii::t('app', '\'s profile') . " </a>";
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has unliked from post on your profile");
												}
											}
											else
											{
												$content = "<a href='/user/" . $event->getEventOwner()->getUsername() .
												           "'>" . Yii::t('app', 'You') . "</a>&nbsp"
												           . Yii::t('app', 'have unliked from a post on your profile');
											}
											break;
										case \common\components\EEvent::FOLLOWS():
											$icon = "fa-eye";
											$color = "";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have followed") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName()  .
													           Yii::t('app', '\'s profile') . " </a>";
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has followed your profile");
												}
											}
											else
											{
												throw new \yii\base\Exception("Impossible event : you cannot follow yourself");
											}
											break;
										case \common\components\EEvent::UNFOLLOWS():
											$icon = "fa-eye-slash";
											$color = "";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have unfollowed") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName()  .
													           Yii::t('app', '\'s profile') . " </a>";
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has unfollowed your profile");
												}
											}
											else
											{
												throw new \yii\base\Exception("Impossible event : you cannot unfollow your profile");
											}
											break;
										case \common\components\EEvent::FRIEND_REQUEST_SENT():
											$icon = "fa-plus-circle";
											$color = "bg-aqua";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have send a friend request to") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName()  . " </a>";
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has send to you a friend request");
												}
											}
											else
											{
												throw new \yii\base\Exception("Impossible event : you cannot sent a friend request to yourself");
											}
											break;
										case \common\components\EEvent::FRIEND_REQUEST_ACCEPTED():
											$icon = "fa-user-plus";
											$color = "bg-orange";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have accepted a friend request from") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName()  . " </a>";
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has accepted your friend request");
												}
											}
											else
											{
												throw new \yii\base\Exception("Impossible event : you cannot accept a friend request to yourself");
											}
											break;
										case \common\components\EEvent::FRIEND_REQUEST_DENIED():
											$icon = "fa-minus-circle";
											$color = "bg-red";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have denied a friend request from") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName()  . " </a>";
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has denied your friend request");
												}
											}
											else
											{
												throw new \yii\base\Exception("Impossible event : you cannot denied a friend request to yourself");
											}
											break;
										case \common\components\EEvent::UNFRIEND():
											$icon = "fa-user-times";
											$color = "bg-red";
											$userConnected = $event->getConnectedUser();
											if ($userConnected != null)
											{
												if ($event->getConnectedData()->self_mode)
												{
													$content = "<a href='/user/" .
													           $event->getEventOwner()->getUsername() . "'>" .
													           Yii::t('app', 'You') . "</a>&nbsp" .
													           Yii::t('app', "have removed ") .
													           "<a href='/user/" . $userConnected->getUsername() .
													           "'> " .
													           $userConnected->getFullName()  . Yii::t('app'," </a> from your friend list");
												}
												else
												{
													$content =
															"<a href='/user/" . $userConnected->getUsername() . "'>" .
															$userConnected->getFullName() . "</a>&nbsp"
															. Yii::t('app', "has removed you from his friend list");
												}
											}
											else
											{
												throw new \yii\base\Exception("Impossible event : you cannot remove yourself from your friendlist");
											}
											break;
										default:
											break;
									}
									if ($dateChanged)
									{
										$lastDate = $event->getEventDate("Y-m-d");
										?>
										<!-- timeline time label -->
										<li class="time-label">
                                <span class="bg-green">
                                    <?= $event->getEventDate("d-m-Y") ?>
                                </span>
										</li>
										<!-- /.timeline-label -->
										<?php
									}
									?>
									<!-- timeline item -->
									<li>
										<i class="fa <?= $icon . " " . $color ?>"></i>

										<div class="timeline-item">
											<span class="time"><i
														class="fa fa-clock-o"></i> <?= $event->getEventDate("H:i:s") ?></span>

											<h3 class="timeline-header no-border">
												<?= $content ?>
											</h3>
										</div>
									</li>
									<!-- END timeline item -->
									<?php

								}
								?>
								<li>
									<i class="fa fa-clock-o bg-gray"></i>
								</li>
							</ul>
						</div>
						<!-- /.tab-pane -->
						
						<div class="tab-pane" id="settings">
							<div class="box box-warning">
								<div class="box-header">
									<h3 class="box-title"><?= Yii::t('app', 'Edit your account'); ?></h3>
								</div>
								<form enctype="multipart/form-data" method="post">
									<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
									       value="<?= Yii::$app->request->csrfToken; ?>"/>
									<div class="box-body">
										<div class="form-group">
											<label><?= Yii::t('app', 'Name'); ?></label>
											<input type="text" value="<?= $loggedUser->getName() ?>"
											       class="form-control"
											       name="inputName" placeholder="Enter name">
										</div>
										<div class="form-group">
											<label><?= Yii::t('app', 'Surname'); ?></label>
											<input type="text" value="<?= $loggedUser->getSurname() ?>"
											       class="form-control"
											       name="inputSurname" placeholder="Enter surname">
										</div>
										<div class="form-group">
											<label><?= Yii::t('app', 'E-mail'); ?></label>
											<input type="email" value="<?= $loggedUser->getEmail() ?>"
											       class="form-control"
											       name="inputEmail" placeholder="Enter email">
										</div>
										<div class="form-group">
											<label><?= Yii::t('app', 'Password'); ?></label>
											<input type="password" class="form-control" name="inputPassword" value=""
											       placeholder="Enter password">
										</div>
										<div class="form-group">
											<label><?= Yii::t('app', 'Repeat password'); ?></label>
											<input type="password" class="form-control" name="inputPasswordRepeat"
											       value="" placeholder="Enter password again">
										</div>
										<div class="form-group">
											<label for="exampleInputFile"><?= Yii::t('app',
														'Change profile picture'); ?></label>
											<input type="file" name="exampleInputFile">
											<p class="help-block"><?= Yii::t('app',
														'Must be less than 300kb and in one of the following formats: png, jpg.'); ?></p>
										</div>
										<input type="hidden" name="type" value="settings">
									</div>
									<div class="box-footer">
										<button type="submit" class="btn btn-primary"><?= Yii::t('app',
													'Save changes'); ?></button>
									</div>
								</form>
							</div>
						</div>
						<!-- /.tab-pane -->
					</div>
					<!-- /.tab-content -->
				</div>
				<!-- /.nav-tabs-custom -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	
	</section>

<?php
$js = <<< JS
       $('.send-form-input').on('keypress', function(e) {
            if (e.charCode==13) {
                $.ajax({
                    url: '/action/addcomment?id='+$(this).attr('post_id'),
                    type: 'post',
                    data: {
                        text: $(this).val()
                    },
                    success: function(r) {
                    }
                });
            }
       });
JS;
$this->registerJs($js);