<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

/**
 * Description of Score
 *
 * @author Mayumu
 */
class Score 
{
    private $element_id;
    private $element_type;
    private $score_id;
    private $score_type;

    public function __construct(EScoreType $scoreType, $scoreId, EScoreElem $elemType, $elemId)
    {
        $this->element_id = $elemId;
        $this->element_type = $elemType;
        $this->score_id = $scoreId;
        $this->score_type = $scoreType;
    }

    public function getElementId()
    {
        return $this->element_id;
    }

    public function getElementType()
    {
        return $this->element_type;
    }

    public function getScoreId()
    {
        return $this->score_id;
    }

    public function getScoreType()
    {
        return $this->score_type;
    }
}
