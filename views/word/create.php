<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Word */

$this->title = Yii::t('app', 'Create Word');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Words'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="word-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
