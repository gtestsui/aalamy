<?php

namespace Modules\Mark\Http\Controllers\Classes;



class MarkServices{


    public static function fill_text($answerBody){
        if(in_array($answerBody['std_answer'],$answerBody['answers']))
            return $answerBody['mark'];
        return 0;
    }

    public static function multi_choice($answerBody){
        foreach ($answerBody['options'] as $option){
            if($option['is_selected'] === true && $option['is_answer'] === true){
                return $answerBody['mark'];
            }
        }
        return 0;

    }


    public static function fill_in_blank($answerBody){
        $mark = 0;
        foreach ($answerBody['answers'] as $answer){
            if($answer['answer'] === $answer['std_answer']){
                $mark+= $answer['mark'];
            }
        }

        return $mark;
    }

    public static function jumble_sentence($answerBody){
        foreach ($answerBody['options'] as $option){
            if($option['is_selected'] && $option['is_true'] ){
                return $answerBody['mark'];
            }
        }
        return 0;

    }

    public static function true_false($answerBody){
        foreach ($answerBody['options'] as $option){
            if($option['is_correct'] == $option['std_answer']){
                return $answerBody['mark'];
            }
        }
        return 0;

    }

    public static function matching($answerBody){
        $mark = 0;
        foreach ($answerBody['options'] as $option){
            foreach ($answerBody['answers'] as $answer){
                if(($option['sentence'] === $answer['left_sentence']) && ($option['match_sentence'] === $answer['right_sentence']))
                    $mark+= $answer['mark'];
            }
        }
        return $mark;

    }

    public static function ordering($answerBody){
        foreach ($answerBody['options'] as $key=>$option){
            if($option !== $answerBody['shuffled'][$key])
                return 0;
        }
        return $answerBody['mark'];
    }




}
