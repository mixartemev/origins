<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lang".
 *
 * @property integer $id
 * @property string $lang
 *
 * @property Word[] $words
 */
class Lang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang'], 'required'],
            [['lang'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lang' => Yii::t('app', 'Lang'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWords()
    {
        return $this->hasMany(Word::className(), ['lang_id' => 'id'])->inverseOf('lang');
    }
}
