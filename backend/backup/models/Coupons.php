<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "coupons".
 *
 * @property integer $coupon_id
 * @property string $coupon_code
 * @property integer $coupon_rate
 */
class Coupons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_code', 'coupon_rate'], 'required'],
            [['coupon_rate'], 'integer'],
            [['coupon_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_id' => 'Coupon ID',
            'coupon_code' => 'Coupon Code',
            'coupon_rate' => 'Coupon Rate',
        ];
    }
}
