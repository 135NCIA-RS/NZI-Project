<?php
namespace common\components;

use app\models\Scores;
use common\components\EScoreElem;
use common\components\EScoreType;
use common\components\Score;


class ScoreService
{

	/**
	 * @param \common\components\EScoreElem $elem
	 * @param              int                   $id
	 *
	 * @return Score[]
	 */
	public static function getScoresByElem(EScoreElem $elem, $id)
	{
		$data = Scores::findAll(['element_id' => $id, 'element_type' => (int)$elem->getValue()]);
		$refined = [];
		foreach($data as $row)
		{
			$scoretype = EScoreType::search($row->score_type);
			$score = new Score(
				EScoreType::$scoretype(),
				$row->score_id,
				$elem,
				$id
			);
			$refined[] = $score;
		}
		return $refined;
	}

	/*
	 * Adds a new score to the database.
	 * $score_type : type of the score e.g. like, dislike (type hinted at EScoreType
	 * $user_id : id of who performed the score action
	 * $score_elem_id : id of the element towards which the score is targeted at
	 * $score_elem_type : type of the element towards which the score is targeted at e.g. post, post_comment, type hinted at EScoreElem
	 */
	public static function addScore(Score $Score, $user_id)
	{
		$score = new Scores();
		$score->score_type = (int)$Score->getScoreType()->getValue();
		$score->user_id = $user_id;
		$score->element_id = $Score->getElementId();
		$score->element_type = (int)$Score->getElementType()->getValue();
		return $score->save();
	}

	//deletes a score with the given id
	public static function revokeScore($score_id)
	{
		$score = Scores::findOne($score_id);
		return isset($score) ? $score->delete() : false;
	}

	public static function revokeScoresByElemId($id, EScoreElem $elem_type)
	{
		$data = Scores::findAll(['element_id' => $id, 'element_type' => $elem_type]);
		foreach ($data as $row)
		{
			$row->delete();
		}
		return true;
	}

	/*
	 * Gets elements sorted by the amount of a certain score
	 * Returns an array of Score objects
	 */
	public static function getElementsByScoreType(EScoreType $score_type, $sort = true)
	{
		$score_type->score_type = (int)$score_type->getValue();
		if ($sort)
		{
			$sql =
				"SELECT COUNT(*) AS `count`, `element_id`, `element_type` FROM `scores` GROUP BY `element_id` ORDER BY 1 DESC;";
			$ptr = Scores::findBySql($sql)->all();
		}
		else
		{
			$ptr = Scores::find()->select(['element_id', 'element_type'])
				->where(['score_type' => (int)$score_type->getValue()])->distinct()->all();
		}
		$ptr2 = [];
		foreach ($ptr as $row)
		{
			$elemType = $row->element_type;
			$elemt = EScoreElem::search($elemType);
			$score = new Score($score_type, $row->score_id, EScoreElem::$elemt(), $row->element_id);
			$ptr2[] = $score;
		}
		return $ptr2;
	}
}
