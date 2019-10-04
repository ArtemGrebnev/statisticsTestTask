<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "statistics".
 *
 * @property string $id
 * @property string $month
 * @property string $user_id
 * @property double $money
 */
class Statistics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statistics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'money'], 'required'],
            [['money'], 'number'],
            [['month', 'user_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'month' => 'Month',
            'user_id' => 'User ID',
            'money' => 'Money',
        ];
    }
}
