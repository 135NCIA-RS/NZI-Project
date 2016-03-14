<?php

use app\models\Scores;
use app\models\ScoreElements;
use app\models\ScoreTypes;

namespace common\components;

class ScoreService {
    
    public static function getPostScores($post_id);
    
    public static function getPostCommentScores($comment_id);
    
    private static function getScoreType($id);
    
    public static function addScore($score_type, $user_id, $score_elem_id, $score_elem_type);
    
    public static function revokeScore($score_id);
    
    private static function getScoreElemType($score);
}
