<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "bposts_rating_reviews".
 *
 * @property string $rating_id
 * @property string $rating_bposts_id
 * @property string $rating_score
 * @property string $rating_review_text
 * @property int $rating_user_id
 * @property int $created_at
 *
 * @property Bposts $ratingBposts
 * @property User $ratingUser
 */
class BpostsRatings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bposts_rating_reviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rating_bposts_id', 'rating_score', 'rating_user_id'], 'required'],
            [['rating_bposts_id', 'rating_user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['rating_score'], 'number'],
            [['rating_review_text'], 'string'],
            [['rating_bposts_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bposts::className(), 'targetAttribute' => ['rating_bposts_id' => 'bpost_id']],
            [['rating_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['rating_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rating_id' => 'Rating ID',
            'rating_bposts_id' => 'Bposts Title',
            'rating_score' => 'Rating',
            'rating_review_text' => 'Review',
            'rating_user_id' => 'User',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRatingBposts()
    {
        return $this->hasOne(Bposts::className(), ['bpost_id' => 'rating_bposts_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRatingUser()
    {
        return $this->hasOne(User::className(), ['id' => 'rating_user_id']);
    }
}
