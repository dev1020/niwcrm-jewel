<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "seats_and_chairs".
 *
 * @property int $id
 * @property string $seatlabel
 * @property string $status
 */
class SeatsAndChairs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seats_and_chairs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seatlabel'], 'required'],
            [['status'], 'string'],
            [['seatlabel'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'seatlabel' => 'Seatlabel',
            'status' => 'Status',
        ];
    }
}
