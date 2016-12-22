<?php

use app\models\Word;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Link */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="link-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'child_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map(Word::find()->all(), 'id', 'word'),
            'options' => ['placeholder' => Yii::t('app', 'Select word')]
        ]
    ) ?>

    <?= $form->field($model, 'parent_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map(Word::find()->all(), 'id', 'word'),
            'options' => ['placeholder' => Yii::t('app', 'Originated from')]
        ]
    ) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
