<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "word".
 *
 * @property integer $id
 * @property string $word
 * @property string $description
 * @property integer $lang_id
 * @property string $created_at
 *
 * @property Link[] $links
 * @property Link[] $links0
 * @property Lang $lang
 */
class Word extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'word';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['word', 'lang_id'], 'required'],
            [['lang_id'], 'integer'],
            [['created_at'], 'safe'],
            [['word', 'description'], 'string', 'max' => 255],
            [['word'], 'unique'],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lang::className(), 'targetAttribute' => ['lang_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'word' => Yii::t('app', 'Word'),
            'description' => Yii::t('app', 'Description'),
            'lang_id' => Yii::t('app', 'Lang ID'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(Link::className(), ['child_id' => 'id'])->inverseOf('child');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks0()
    {
        return $this->hasMany(Link::className(), ['parent_id' => 'id'])->inverseOf('parent');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id'])->inverseOf('words');
    }
}
