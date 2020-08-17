<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wrong Inputs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrong-input-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Wrong Input', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute'=>'shop_id',
                'label'=>'Магазин',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getParentName();
                },
            ],
            'second_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
