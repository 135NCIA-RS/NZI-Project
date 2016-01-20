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
use app\components\PostsService;
use app\components\UserService;
use app\components\PhotoService;
/* @var $this yii\web\View */
?>

<section class="content">

    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <?= Html::img($UserProfilePhoto, ['class' => 'profile-user-img img-responsive img-circle', 'alt' => 'User profile image']) ?>
                    <h3 class="profile-username text-center"><?= $name . " " . $surname ?></h3>

                    <p class="text-muted text-center">Software Engineer</p>
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
                    ?>
                    <?php Pjax::begin(); ?>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Followers</b> <a class="pull-right"><?= $followers ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Following</b> <a class="pull-right"><?= $following ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Friends</b> <a class="pull-right"><?= $friends ?></a>
                        </li>
                    </ul>
                    <style>
                        .btnodst{
                            margin-top: 5px;
                        }
                    </style>
                    <?php
                    echo Html::beginForm("", 'post', ['data-pjax' => '']);
                    if (!$UserFollowState)
                    {
                        echo Html::submitButton("Follow", [
                            'class' => 'btn btn-primary btn-block btnodst pjaxButton',
                            'name' => 'follow-btn',
                            ]);
                    }
                    else
                    {
                        echo Html::submitButton("Unfollow", [
                            'class' => 'btn btn-default btn-block btn-sm btnodst pjaxButton',
                            'name' => 'unfollow-btn',
                            ]);
                    }
                    echo Html::endForm();

                    echo Html::beginForm("", 'post', ['data-pjax' => '']);
                    if (!$UserFriendshipState)
                    {
                        echo Html::submitButton("Request Friendship", [
                            'class' => 'btn btn-primary btn-block btnodst pjaxButton',
                            'name' => 'friend-btn',
                            ]);
                    }
                    else
                    {
                        echo Html::submitButton("Unfriend", [
                            'class' => 'btn btn-default btn-block btn-sm btnodst pjaxButton',
                            'name' => 'unfriend-btn',
                            ]);
                    }
                    echo Html::endForm();

                    echo Html::a("Refresh", [""], ['class' => 'hidden', 'id' => 'refr']);

                    Pjax::end();
                    ?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">About Me</h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

                    <p class="text-muted">
                        <?= $education ?>
                    </p>

                    <hr>

                    <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

                    <p class="text-muted"><?= $city ?></p>

                    <hr>

                    <strong><i class="fa fa-birthday-cake margin-r-5"></i> Date of birth</strong>

                    <p>
                        <?= $birth ?>
                    </p>

                    <hr>

                    <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

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
                    <li class="active"><a href="#activity" data-toggle="tab">Activity</a></li>
                    <li><a href="#timeline" data-toggle="tab">Timeline</a></li>

                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                        <!-- Post -->
                        <?php
                        
                        foreach ($posts as $row) {
                            if($row['post_visibility']=="visible")
                            {
                            ?>
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="<?php echo $photo;?>" alt="user image">
                                <span class="username">
                                    <a href="#"><?php echo($row['name']." ".$row['surname']); ?></a>
                                    <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                </span>
                                <span class="description"><?php if($row['post_visibility']=="visible") echo "Post public"; else echo "Post hidden"; ?> - <?php echo($row['post_date']); ?></span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                <?php 
                                $attachments = $row['attachments'];
                                if($row['post_type']=="text")
                                    echo $row['post_text']; 
                                else if($row['post_type']=="gallery")
                                {
                                    echo $row['post_text']."<br>";
                                    ?>
                                    <div class="row margin-bottom">
                                    <div class="col-sm-6">
                                        <img class="img-responsive" src="../../dist/content/attachments/<?php echo $attachments[0]['file']; ?>" alt="Photo">
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <img class="img-responsive" src="../../dist/content/attachments/<?php echo $attachments[1]['file']; ?>" alt="Photo">
                                                <br>
                                                <img class="img-responsive" src="../../dist/content/attachments/<?php echo $attachments[2]['file']; ?>" alt="Photo">
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-sm-6">
                                                <img class="img-responsive" src="../../dist/content/attachments/<?php echo $attachments[3]['file']; ?>" alt="Photo">
                                                <br>
                                                <?php if(isset($attachments[4]['file'])) { ?><img class="img-responsive" src="../../dist/content/attachments/<?php echo $attachments[1]['file']; ?>" alt="Photo"> <?php } ?>
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
                                <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                                <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                                </li>
                                <li class="pull-right">
                                    <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments (<?php echo(count($row['comments'])); ?>)</a></li>
                            </ul>
                            <input class="form-control input-sm send-form-input" type="text" placeholder="Type a comment" post_id="<?=$row['post_id']?>" >
                            <div class="direct-chat-msg" style="margin-top: 10px;">
                      <div class="direct-chat-info clearfix">
                      </div>
                      <!-- /.direct-chat-info -->
                      <?php
                      foreach ($row['comments'] as $comment) {
                      ?>
                      <div style="background-color: #EDF5F7; padding: 10px 10px 1px 10px; border-radius: 10px; margin-left: 30px; margin-bottom:5px;">
                      <img class="direct-chat-img" src="<?php echo $comment['photo'];?>" alt="message user image" style="margin-right: 10px;"><!-- /.direct-chat-img -->
                      <p class="message" >
                  <a href="#" class="name">
                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php echo $comment['comment_date']; ?></small>
                    <?php echo($comment['name']." ".$comment['surname']); ?><br>
                  </a>
                  <?php echo $comment['comment_text']; ?>
                </p>
                      </div> 
                          <?php } ?>
                      <!-- /.direct-chat-text -->
                    </div>
                        </div> 
                        
                        <?php
                            }}
                        ?>
                        <!-- /.post -->
                    </div>
                    <!-- /.tab-pane -->
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

                                    </div>
                                </div>
                            </li>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <li>
                                <i class="fa fa-user bg-aqua"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                                    <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                                    </h3>
                                </div>
                            </li>
                            <!-- END timeline item -->
                            <!-- timeline item -->
                            <li>
                                <i class="fa fa-comments bg-yellow"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                                    <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

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

                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

</section>