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

                    if ($posts== null)
                    {
                    echo 'Nothing to show';
                    }

                    foreach ($posts as $row)
                    {
                        ?>
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="<?php echo $row['photo']; ?>"
                                     alt="user image">
                                    <span class="username">
                                        <?php echo Html::beginForm(['site/repcomment'], 'post', ['data-pjax' => '']) ?>
                                        <input type="hidden" name="action" value="delete">
                                         <input type="hidden" name="post_id" value="<?= $row['comment_id'] ?>">
                                         <button style="..." type="submit"
                                                 class="pull-right fa fa-times"></button>
                                        <?= Html::endForm() ?>

                                        <?php echo Html::beginForm(['site/repcomment'], 'post', ['data-pjax' => '']) ?>
                                        <input type="hidden" name="action" value="revoke">
                                         <input type="hidden" name="post_id" value="<?= $row['comment_id'] ?>">
                                         <button style="..." type="submit"
                                                 class="pull-right fa fa-minus-circle"></button>
                                        <?= Html::endForm() ?>
                                        <small class="text-muted pull-right"><i
                                                    class="fa fa-clock-o"></i> <?php echo $row['comment_date']; ?>

                                        </small>
                                        <a href="#"><?php echo($row['name'] . " " . $row['surname']); ?></a>


                                        <!--                                        <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>-->





                                    </span>
                                <!--                                    <span class="description">-->
                                <?php //if ($row['post_visibility'] == "visible")
                                //	                                    {
                                //		                                    echo Yii::t('app', 'Post public');
                                //	                                    }
                                //	                                    else
                                //	                                    {
                                //		                                    echo Yii::t('app', 'Post hidden');
                                //	                                    }
                                //
                                ?><!-- - --><?php //echo($row['post_date']);
                                ?><!--</span>-->
                            </div>
                            <!-- /.user-block -->
                            <p>
                                <?php

                                echo $row['comment_text'];


                                ?>

                                <?php

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
