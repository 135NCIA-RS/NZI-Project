<?php
namespace common\components;

use app\models\Scores;
use common\components\ScoreElemEnum;
use common\components\ScoreTypeEnum;
use common\components\Score;


class ScoreService {
    
    //gets all scores for a certain post_id
    public static function getPostScores($post_id)
    {
        $data = Scores::findAll(['element_id' => $post_id, 'element_type' => 1]);
        $counter = 0;
        foreach ($data as $row)
	{
		$refined_data[$counter]['score_id'] = $row['score_id'];
                $refined_data[$counter]['user_id'] = $row['user_id'];
                $refined_data[$counter]['score_type'] = ScoreService::getScoreType($row['score_type']);
		$counter++;
	}
	return isset($refined_data) ? $refined_data : [];
    }
    
    //gets all scores for a certain comment_id
    public static function getPostCommentScores($comment_id)
    {
        $data = Scores::findAll(['element_id' => $comment_id, 'element_type' => 2]);
        $counter = 0;
        foreach ($data as $row)
	{
		$refined_data[$counter]['score_id'] = $row['score_id'];
                $refined_data[$counter]['user_id'] = $row['user_id'];
                $refined_data[$counter]['score_type'] = ScoreService::getScoreType($row['score_type']);
		$counter++;
	}
	return isset($refined_data) ? $refined_data : [];
    }
    
    //returns a string of what type the elem is, returns false if such a type doesn't exist
    private static function getScoreType($id)
    {
        return ScoreTypeEnum::search($id);
    }
    
    /*
     * Adds a new score to the database.
     * $score_type : type of the score e.g. like, dislike (type hinted at ScoreTypeEnum
     * $user_id : id of who performed the score action
     * $score_elem_id : id of the element towards which the score is targeted at
     * $score_elem_type : type of the element towards which the score is targeted at e.g. post, post_comment, type hinted at ScoreElemEnum
     */
    public static function addScore(ScoreTypeEnum $score_type, $user_id, $score_elem_id, ScoreElemEnum $score_elem_type)
    {
        $score = new Scores();
        $score->score_type = (int)$score_type->getValue();
        $score->user_id = $user_id;
        $score->element_id = $score_elem_id;
        $score->element_type = (int)$score_elem_type->getValue();
        return $score->save();
    }
    
    //deletes a score with the given id
    public static function revokeScore($score_id)
    {
        $score = Scores::findOne($score_id);
        return isset($score) ? $score->delete() : false;
    }
    
    /*
     * Gets elements sorted by the amount of a certain score
     * Returns an array of Score objects
     */
    public static function getElementsByScoreType(ScoreTypeEnum $score_type, $sort = true)
    {
        $score_type->score_type = (int)$score_type->getValue();
        if($sort)
        {
            $sql = "SELECT COUNT(*) AS `count`, `element_id`, `element_type` FROM `scores` GROUP BY `element_id` ORDER BY 1 DESC;";
            $ptr = Scores::findBySql($sql)->all();
        }
        else
        {
            $ptr = Scores::find()->select(['element_id', 'element_type'])->where(['score_type'=> (int)$score_type->getValue()])->distinct()->all();
        }
        $ptr2 = [];
        foreach($ptr as $row)
        {
            $score = new Score();
            $score->element_id = $row->element_id;
            $score->element_type = $row->element_type;
            array_push($ptr2, $score);
        }
        return $ptr2;
    }
    
    //returns a string of what the elem is, returns false if such an elem doesn't exist
    /*private static function getScoreElemType($elem_id)
    {
        return ScoreElemEnum::search($elem_id);
    } not needed, I guess?*/
}
