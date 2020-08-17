<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coupons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Coupon', ['create'], ['class' => 'btn btn-success']) ?>
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
            'coupon_id',
            'category',
            'name',
            'description',
            //'logo:ntext',
            'promocode',
            //'promolink:ntext',
            //'gotolink:ntext',
            //'date_start',
            //'date_end',
            'discount',
            //'create',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
