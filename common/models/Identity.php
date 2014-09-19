<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%identity}}".
 *
 * @property integer $id
 * @property integer $internal_user_id
 * @property string $auth_provider
 * @property string $external_user_id
 *
 * @property User $internalUser
 */
class Identity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%identity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
      return [
        [['internal_user_id', 'auth_provider', 'external_user_id'], 'required'],
        [['internal_user_id'], 'integer'],
        [['auth_provider', 'external_user_id'], 'string', 'max' => 32],
        [['auth_provider', 'external_user_id'], 'unique', 'targetAttribute' => ['auth_provider', 'external_user_id'], 'message' => 'The combination of Auth Provider and External User ID has already been taken.']
      ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'internal_user_id' => 'Internal User ID',
            'auth_provider' => 'Auth Provider',
            'external_user_id' => 'External User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInternalUser()
    {
        return $this->hasOne(User::className(), ['id' => 'internal_user_id']);
    }
}
