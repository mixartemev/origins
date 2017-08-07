<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "link".
 *
 * @property integer $id
 * @property integer $child_id
 * @property integer $parent_id
 * @property string $description
 * @property integer $user_id
 * @property string $created_at
 *
 * @property Word $child
 * @property Word $parent
 * @property User $user
 */
class Link extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['child_id', 'parent_id', 'user_id'], 'integer'],
            [['child_id', 'parent_id'/*, 'user_id'*/], 'required'],
            [['created_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [['child_id'], 'unique', 'targetAttribute' => ['child_id', 'parent_id']], //уникальность комбинации полей
            [['child_id'], 'exist', 'skipOnError' => true, 'targetClass' => Word::className(), 'targetAttribute' => ['child_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Word::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'child.word' => Yii::t('app', 'Child'),
            'parent.word' => Yii::t('app', 'Parent'),
            'description' => Yii::t('app', 'Description'),
            'user.name' => Yii::t('app', 'User'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild()
    {
        return $this->hasOne(Word::className(), ['id' => 'child_id'])->inverseOf('links');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Word::className(), ['id' => 'parent_id'])->inverseOf('links0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('links');
    }

    public function beforeSave($insert)
    {
        if($insert){
            $this->user_id = Yii::$app->user->id ?: 0;
        }
        return parent::beforeSave($insert);
    }
}
