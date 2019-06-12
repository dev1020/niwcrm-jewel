<?php
namespace common\components;
 
use Yii;
use yii\base\Component;


class Multilevel extends Component {

    public static function makeDropDown($level, $model) {
        global $data;
		$parents = $model->findAll(['category_root' => $level]);
        $data = array();
        $data['0'] = 'Select Parent';
        foreach ($parents as $parent) {
            $data[$parent->category_id] = $parent->category_name;
            self::subDropDown($parent->category_id, $space = ' --', $model);
        }
        return $data;
    }

    public static function subDropDown($children, $space = ' --', $model) {
        global $data;
        $childrens = $model->findAll(['category_root' => $children, 'category_status'=>'Active']);
        foreach ($childrens as $child) {
            $data[$child->category_id] = $space . $child->category_name;
            self::subDropDown($child->category_id, $space . ' --', $model);
        }
    }
	
	public static function makeDropDownClassified($level, $model) {
        global $data;
		$parents = $model->findAll(['classified_cat_root' => $level]);
        $data = array();
        $data['0'] = 'Select Parent';
        foreach ($parents as $parent) {
            $data[$parent->classified_cat_id] = $parent->classified_cat_name;
            self::subDropDownClassified($parent->classified_cat_id, $space = ' --', $model);
        }
        return $data;
    }

    public static function subDropDownClassified($children, $space = ' --', $model) {
        global $data;
        $childrens = $model->findAll(['classified_cat_root' => $children, 'classified_cat_status'=>'Active']);
        foreach ($childrens as $child) {
            $data[$child->classified_cat_id] = $space . $child->classified_cat_name;
            self::subDropDownClassified($child->classified_cat_id, $space . ' --', $model);
        }
    }

}
