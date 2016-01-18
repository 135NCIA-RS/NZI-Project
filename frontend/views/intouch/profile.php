<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
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
                    <?= Html::img($this->params['userProfilePhoto'], ['class' => 'profile-user-img img-responsive img-circle', 'alt' => 'User profile image']) ?>
                    <h3 class="profile-username text-center"><?= $this->params['userInfo']['user_name'] . " " . $this->params['userInfo']['user_surname'] ?></h3>

                    <p class="text-muted text-center">Software Engineer</p>

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

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">About Me</h3>
                    <span class="pull-right"><a href="profile/aboutedit" ><i class="fa fa-cog" ></i></a></span>
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
                    <li><a href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                        <!-- Add post -->
                        <form class="form-horizontal">
                            <input class="form-control input-sm send-form-input" row="3" type="text" placeholder="Post" >
                            <button style="width:20%; margin-top:5px;" type="submit" class="btn btn-danger btn-block" >Publish</button>
                            <hr>
                        </form>
                        <!-- /Add post-->
                        <!-- Post -->
                        <?php
                        $id = Yii::$app->user->getId();
                        $posts = PostsService::getPosts($id);
                        foreach ($posts as $row) {
                            ?>
                       
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="../../dist/content/images/<?php echo PhotoService::getProfilePhoto($id);?>" alt="user image">
                                <span class="username">
                                    <a href="#"><?php echo(UserService::getName($id)." ".UserService::getSurname($id)); ?></a>
                                    <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                </span>
                                <span class="description"><?php if($row['post_visibility']=="visible") echo "Post public"; else echo "Post hidden"; ?> - <?php echo(PostsService::getPostDate($row['post_id'])); ?></span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                <?php 
                                $attachments = PostsService::getAttachments($row['post_id']);
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
                                    <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments (<?php echo(PostsService::getNumberOfComments($row['post_id'])); ?>)</a></li>
                            </ul>

                            <input class="form-control input-sm send-form-input" type="text" placeholder="Type a comment" post_id="<?=$row['post_id']?>" >
                            
                            <div class="direct-chat-msg" style="margin-top: 10px;">
                      <div class="direct-chat-info clearfix">
                      </div>
                      <!-- /.direct-chat-info -->
                      <?php $comments = PostsService::getComments($row['post_id']);
                      foreach ($comments as $comment) {
                      ?>
                      <div style="background-color: #EDF5F7; padding: 10px 10px 1px 10px; border-radius: 10px; margin-left: 30px; margin-bottom:-20px;">
                      <img class="direct-chat-img" src="../../dist/content/images/<?php echo PhotoService::getProfilePhoto($comment['author_id']);?>" alt="message user image" style="margin-right: 10px;"><!-- /.direct-chat-img -->
                      <p class="message" >
                  <a href="#" class="name">
                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php echo $comment['comment_date']; ?></small>
                    <?php echo(UserService::getName($comment['author_id'])." ".UserService::getSurname($comment['author_id'])); ?><br>
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
                        ?>
                        <!-- /.post -->
                        
                        <!-- Post -->
                        <div class="post clearfix">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                                <span class="username">
                                    <a href="#">Sarah Ross</a>
                                    <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                </span>
                                <span class="description">Sent you a message - 3 days ago</span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                Lorem ipsum represents a long-held tradition for designers,
                                typographers and the like. Some people hate it and argue for
                                its demise, but others ignore the hate as they create awesome
                                tools to help create filler text for everyone from bacon lovers
                                to Charlie Sheen fans.
                            </p>

                            <form class="form-horizontal">
                                <div class="form-group margin-bottom-none">
                                    <div class="col-sm-9">
                                        <input class="form-control input-sm" placeholder="Response">
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-danger pull-right btn-block btn-sm">Send</button>  
                                    </div>
                                </div>
                            </form>
                        </div>
                    <!-- /.tab-pane -->
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
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="settings">
                        <div class="box box-warning">
                            <div class="box-header">
                                <h3 class="box-title">Edit your account</h3>
                            </div>
                            <form enctype="multipart/form-data" method="post">
                                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" value="<?= $name ?>" class="form-control" name="inputName" placeholder="Enter name">
                                    </div>
                                    <div class="form-group">
                                        <label>Surname</label>
                                        <input type="text" value="<?= $surname ?>" class="form-control" name="inputSurname" placeholder="Enter surname">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" value="<?= $email ?>" class="form-control" name="inputEmail" placeholder="Enter email">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="inputPassword" value="" placeholder="Enter password">
                                    </div>
                                    <div class="form-group">
                                        <label>Repeat password</label>
                                        <input type="password" class="form-control" name="inputPasswordRepeat" value="" placeholder="Enter password again">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Change profile's picture</label>
                                        <input type="file" name="exampleInputFile">
                                        <p class="help-block">Must be less than 300kb in size and in one of the following formats: jpg, png.</p>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
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