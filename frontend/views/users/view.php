<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\components\PostsService;
use common\components\UserService;
use common\components\PhotoService;

/* @var $this yii\web\View */
/* @var $user \common\components\IntouchUser */
/* @var $posts \common\components\Post[] */
/* @var $myUser \common\components\IntouchUser */
$myUser = $this->params['userInfo'];
?>

<section class="content">

    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <?= Html::img($user->getImageUrl(),
                            ['class' => 'profile-user-img img-responsive img-circle', 'alt' => 'User profile image']) ?>
                    <h3 class="profile-username text-center"><?= $user->getFullName() ?></h3>

                    <p class="text-muted text-center"><?= Yii::t('app', 'InTouch User'); ?></p>

                    <?php
                    $script = <<< JS
 $('body').on('click', '.pjaxButton', function() {
         setTimeout(
             function() {
                 $("#refr").click();
             },
             1250);
 });
JS;
                    $this->registerJs($script, yii\web\View::POS_READY);
                    Pjax::begin();
                    ?>
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
                    <style>
                        .btnodst {
                            margin-top: 5px;
                        }
                    </style>
                    <?php
                    echo Html::beginForm(["users/view", 'uname' => $user->getUsername()], 'post', ['data-pjax' => '']);

                    if (!$UserFollowState)
                    {
                        echo Html::submitButton(Yii::t('app', 'Follow'), [
                                'class' => 'btn btn-primary btn-block btnodst pjaxButton',
                                'name' => 'follow-btn',
                        ]);
                    }
                    else
                    {
                        echo Html::submitButton(Yii::t('app', 'Unfollow'), [
                                'class' => 'btn btn-default btn-block btn-sm btnodst pjaxButton',
                                'name' => 'unfollow-btn',
                        ]);
                    }
                    echo Html::endForm();

                    if (is_bool($UserFriendshipState))
                    {
                        echo Html::beginForm(["users/view", 'uname' => $user->getUsername()], 'post', ['data-pjax' => '']);
                        if (!$UserFriendshipState)
                        {
                            echo Html::submitButton(Yii::t('app', 'Send a friend request'), [
                                    'class' => 'btn btn-primary btn-block btnodst pjaxButton',
                                    'name' => 'friend-btn',
                            ]);
                        }
                        else
                        {
                            echo Html::submitButton(Yii::t('app', 'Unfriend'), [
                                    'class' => 'btn btn-default btn-block btn-sm btnodst pjaxButton',
                                    'name' => 'unfriend-btn',
                            ]);
                        }
                        echo Html::endForm();
                    }
                    else
                    {
                        echo "<button class='btn btn-default btn-block btn-sm'>Friend Request Sent</button>";
                    }
                    echo Html::a("Refresh", ["users/view", 'uname' => $user->getUsername()],
                            ['class' => 'hidden', 'id' => 'refr']);
                    Pjax::end();
                    ?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('app', 'About me'); ?></h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <strong><i class="fa fa-book margin-r-5"></i> <?= Yii::t('app', 'Education'); ?></strong>

                    <p class="text-muted">
                        <?= $user->getEducation() ?>
                    </p>

                    <hr>

                    <strong><i class="fa fa-map-marker margin-r-5"></i> <?= Yii::t('app', 'Location'); ?></strong>

                    <p class="text-muted"><?= $user->getCity() ?></p>

                    <hr>

                    <strong><i class="fa fa-birthday-cake margin-r-5"></i> <?= Yii::t('app', 'Birthday'); ?></strong>

                    <p>
                        <?= $user->getBirthDate() ?>
                    </p>

                    <hr>

                    <strong><i class="fa fa-file-text-o margin-r-5"></i> <?= Yii::t('app', 'Miscellaneous'); ?></strong>

                    <p><?= $user->getAbout() ?></p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#activity" data-toggle="tab"><?= Yii::t('app', 'Activity'); ?></a></li>
                </ul>
                <div class="tab-content">
                    <?php ?>
                    <div class="active tab-pane" id="activity">
                        <?php
                        yii\widgets\Pjax::begin();
                        ?>

                        <!-- Add post -->
                        <?= Html::beginForm(["users/view", 'uname' => $user->getUsername()], 'post', ['data-pjax' => '']) ?>
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
                                class="btn btn-danger pull-right btn-primary btn-sm"><?= Yii::t('app', 'Publish'); ?></button>
                        <hr>
                        <?= Html::endForm() ?>
                        <!-- /Add post-->

                        <!-- Post -->
                        <?php
                        foreach ($posts as $row)
                        {
                            $author = $row->getAuthor();
                            $comments = $row->getComments();
                            if ($row->checkVisibility(\common\components\EVisibility::visible()))
                            {
                                ?>
                                <div class="post">
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="<?= $author->getImageUrl() ?>"
                                             alt="user image">
                                        <span class="username">
                                            <a href="user/<?= $author->getUsername() ?>"><?= $author->getFullName() ?></a>

                                            <?= Html::beginForm(["users/view", 'uname' => $user->getUsername()], 'post',
                                                    ['data-pjax' => '']) ?>
                                            <input type="hidden" name="post_id" value="<?= $row->getId() ?>">
                                            <input class="" type="hidden" name="type" value="delete_post"
                                                   id="delete_post-form">
                                            <button style="border: none; margin-top: 2px" type="submit" class="pull-right btn-box-tool fa fa-times"></button>
                                            <?= Html::endForm() ?>

                                            <!--                                            <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>-->
                                            <?php
                                            $postOwner=$author->getId();
                                            if(Yii::$app->user->getId() == $postOwner || Yii::$app->user->can('admin'))
                                                echo '<button type="button"
                                                                    onclick="window.location.href=\'/post/edit/'.$row->getId().' \'"
															        class="btn btn-box-tool dropdown-toggle"
															        data-toggle="dropdown">
																<i class="fa fa-wrench"></i></button>';
                                            ?>
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
                                                    <?php echo Html::beginForm(["users/view", 'uname' => $user->getUsername()], 'post', ['data-pjax' => '']) ?>
                                                    <input type="hidden" name="post_id" value="<?= $row->getId() ?>">
                                                    <input type="hidden" name="score_elem" value="post">
                                                    <input type="hidden" name="user_id" value="<?= $myUser->getId() ?>">
                                                    <input class="" type="hidden" name="type" value="like" id="like-form">
                                                    <span class="link-black text-sm">
                                                        <i class="fa fa-thumbs-o-up"></i>
                                                        <input type="submit" class="fa fa-t humbs-o-up link-black text-sm"
                                                               style="background: none; border: none;"
                                                               value="<?=
                                                               Yii::t('app', 'Like') .
                                                               " (" .
                                                               $row->countScoresByType(\common\components\EScoreType::like()) .
                                                               ")"
                                                               ?> ">
                                                    </span>
                                                    <?= Html::endForm() ?>
                                                </li>
                                        <li class="pull-right">
                                            <a href="#" class="link-black text-sm"><i
                                                        class="fa fa-comments-o margin-r-5"></i> <?= Yii::t('app',
                                                        'Comments'); ?> (<?= (count($comments)); ?>)</a>
                                        </li>
                                        <li class="pull-right">
                                            <a href="#" class="link-black text-sm"><i
                                                        class="fa fa-exclamation margin-r-5"></i><?= Yii::t('app',
                                                        'Report'); ?></a>
                                        </li>
                                    </ul>
                                    <?= Html::beginForm(["users/view", 'uname' => $user->getUsername()], 'post',
                                            ['data-pjax' => '']) ?>
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
                                            $commAuthor = $comment->getAuthor();
                                            ?>
                                            <div style="background-color: #EDF5F7; padding: 10px 10px 1px 10px; border-radius: 10px; margin-left: 30px; margin-bottom:5px;">
                                                <img class="direct-chat-img" src="<?= $commAuthor->getImageUrl() ?>"
                                                     alt="message user image" style="margin-right: 10px;">
                                                <!-- /.direct-chat-img -->
                                                <p class="message">
                                                    <a href="#" class="name">
                                                        <small class="text-muted pull-right">
	                                                        <?= Html::beginForm(["users/view", 'uname' => $user->getId()], 'post',
			                                                        ['data-pjax' => '']) ?>
	                                                        <input type="hidden" name="comment_id"
	                                                               value="<?= $comment->getId() ?>">
	                                                        <input class="" type="hidden" name="type"
	                                                               value="delete_comment" id="delete_comment-form">
	                                                        <button style="border: none; margin-top: -4px" type="submit"
	                                                                class="pull-right btn-box-tool fa fa-times"></button>
	                                                        <?= Html::endForm() ?>
	                                                        <i class="fa fa-clock-o"></i> <?= $comment->getDate() ?>
                                                            <?php

                                                            $nothing = $comment->getAuthor();
                                                            $commentOwner= $nothing->getId();
                                                            if(Yii::$app->user->getId() == $commentOwner || Yii::$app->user->can('admin'))
                                                                echo '<button type="button"
                                                                    onclick="window.location.href=\'/post/commentEdit/'.$comment->getId().'\'"
															        class="btn btn-box-tool dropdown-toggle"
															        data-toggle="dropdown">
																<i class="fa fa-wrench"></i></button>';
                                                            ?>

                                                        </small>
                                                        <?= $commAuthor->getFullName() ?><br>
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
                        }

                        yii\widgets\Pjax::end();
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