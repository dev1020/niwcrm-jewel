<?php

namespace common\models;

use Yii;


/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class Elastic extends \yii\elasticsearch\ActiveRecord
{
 
    public function attributes()
    {
 
        return['name', 'email'];
 
    }
 
}
