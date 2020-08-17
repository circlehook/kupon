<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\WrongInput */

$this->title = 'Create Wrong Input';
$this->params['breadcrumbs'][] = ['label' => 'Wrong Inputs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrong-input-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
