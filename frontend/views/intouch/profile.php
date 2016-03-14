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
use yii\widgets\Pjax

/* @var $this yii\web\View */
?>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <?= Html::img($this->params['userProfilePhoto'],
                                ['class' => 'profile-user-img img-responsive img-circle',
                                 'alt' => 'User profile image']) ?>
                        <h3 class="profile-username text-center"><?= $this->params['userInfo']['user_name'] . " " .
                                                                     $this->params['userInfo']['user_surname'] ?></h3>
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
                            <?= $education ?>
                        </p>
                        <hr>
                        <strong><i class="fa fa-map-marker margin-r-5"></i> <?= Yii::t('app', 'Location'); ?></strong>
                        <p class="text-muted"><?= $city ?></p>
                        <hr>

                        <strong><i class="fa fa-birthday-cake margin-r-5"></i> <?= Yii::t('app', 'Birthday'); ?>
                        </strong>

                        <p>
                            <?= $birth ?>
                        </p>

                        <hr>

                        <strong><i class="fa fa-file-text-o margin-r-5"></i> <?= Yii::t('app', 'Miscellaneous'); ?>
                        </strong>

                        <p><?= $about ?></p>
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
                            <?php  echo Html::beginForm(['intouch/profile'], 'post', ['data-pjax' => '']) ?>
                            <input class="form-control input-sm send-form-input" row="3" type="text" placeholder="Post"
                                   name="inputText">
                            <input type="hidden" name="type" value="newpost">
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
                                ?>
                                <div class="post">
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="<?php echo $row['photo']; ?>"
                                             alt="user image">
                                    <span class="username">
                                        <a href="#"><?php echo($row['name'] . " " . $row['surname']); ?></a>
                                        <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
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
                                    <ul class="list-inline">
                                        <li><a href="#" class="link-black text-sm"><i
                                                        class="fa fa-share margin-r-5"></i> <?= Yii::t('app',
                                                        'Share'); ?></a></li>
                                        <li><a href="#" class="link-black text-sm"><i
                                                        class="fa fa-thumbs-o-up margin-r-5"></i> <?= Yii::t('app',
                                                        'Like'); ?></a>
                                        </li>
                                        <li class="pull-right">
                                            <a href="#" class="link-black text-sm"><i
                                                        class="fa fa-comments-o margin-r-5"></i> <?= Yii::t('app',
                                                        'Comments'); ?> (<?php echo(count($row['comments'])); ?>)</a>
                                        </li>
                                    </ul>
                                    <?php  echo Html::beginForm(['intouch/profile'], 'post', ['data-pjax' => '']) ?>
                                    <input class="form-control input-sm send-form-input" type="text"
                                           placeholder="<?= Yii::t('app', 'Type a comment'); ?>" name="inputText">
                                    <input type="hidden" name="type" value="newcomment">
                                    <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                                    <button style="width:20%; margin-top:5px;" type="submit"
                                            class="btn btn-danger btn-block btn-sm hidden"></button>
                                    <?= Html::endForm() ?>
                                    <div class="direct-chat-msg" style="margin-top: 10px;">
                                        <div class="direct-chat-info clearfix">
                                        </div>
                                        <!-- /.direct-chat-info -->
                                        <?php
                                        foreach ($row['comments'] as $comment)
                                        {
                                            ?>
                                            <div style="background-color: #EDF5F7; padding: 10px 10px 1px 10px; border-radius: 10px; margin-left: 30px; margin-bottom:5px;">
                                                <img class="direct-chat-img" src="<?php echo $comment['photo']; ?>"
                                                     alt="message user image" style="margin-right: 10px;">
                                                <!-- /.direct-chat-img -->
                                                <p class="message">
                                                    <a href="#" class="name">
                                                        <small class="text-muted pull-right"><i
                                                                    class="fa fa-clock-o"></i> <?php echo $comment['comment_date']; ?>
                                                        </small>
                                                        <?php echo($comment['name'] . " " . $comment['surname']); ?><br>
                                                    </a>
                                                    <?php echo $comment['comment_text']; ?>
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
                                    10 Feb. 2014
                                </span>
                                </li>
                                <!-- /.timeline-label -->
                                <!-- timeline item -->
                                <li>
                                    <i class="fa fa-envelope bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>
                                        <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>
                                        <div class="timeline-body">
                                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                            weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                            quora plaxo ideeli hulu weebly balihoo...
                                        </div>
                                        <div class="timeline-footer">
                                            <a class="btn btn-primary btn-xs">Read more</a>
                                            <a class="btn btn-danger btn-xs">Delete</a>
                                        </div>
                                    </div>
                                </li>
                                <!-- END timeline item -->
                                <!-- timeline item -->
                                <li>
                                    <i class="fa fa-user bg-aqua"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                                        <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your
                                            friend request
                                        </h3>
                                    </div>
                                </li>
                                <!-- END timeline item -->
                                <!-- timeline item -->
                                <li>
                                    <i class="fa fa-comments bg-yellow"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                                        <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post
                                        </h3>

                                        <div class="timeline-body">
                                            Take me to your leader!
                                            Switzerland is small and neutral!
                                            We are more like Germany, ambitious and misunderstood!
                                        </div>
                                        <div class="timeline-footer">
                                            <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                                        </div>
                                    </div>
                                </li>
                                <!-- END timeline item -->
                                <!-- timeline time label -->
                                <li class="time-label">
                                <span class="bg-green">
                                    3 Jan. 2014
                                </span>
                                </li>
                                <!-- /.timeline-label -->
                                <!-- timeline item -->
                                <li>
                                    <i class="fa fa-camera bg-purple"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                                        <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                                        <div class="timeline-body">
                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                        </div>
                                    </div>
                                </li>
                                <!-- END timeline item -->
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
                                            <input type="text" value="<?= $name ?>" class="form-control"
                                                   name="inputName" placeholder="Enter name">
                                        </div>
                                        <div class="form-group">
                                            <label><?= Yii::t('app', 'Surname'); ?></label>
                                            <input type="text" value="<?= $surname ?>" class="form-control"
                                                   name="inputSurname" placeholder="Enter surname">
                                        </div>
                                        <div class="form-group">
                                            <label><?= Yii::t('app', 'E-mail'); ?></label>
                                            <input type="email" value="<?= $email ?>" class="form-control"
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
