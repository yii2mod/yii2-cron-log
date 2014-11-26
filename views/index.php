<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cron Schedule Log';

$this->params['breadcrumbs'][] = ['label' => \yii\helpers\Inflector::titleize($this->context->module->id),
    'url' => $this->context->module->id
];
$this->params['breadcrumbs'][] = ['label' => \yii\helpers\Inflector::titleize($this->context->id),
    'url' => $this->context->module->id . '/' . $this->context->id
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-schedule-model-index">

    <h1><?php echo Html::encode($this->title) ?></h1>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'Showing {begin}-{end} of {totalCount}.',
        'columns' => [
            'id',
            'jobCode',
            'status',
            'messages:ntext',
            'dateCreated',
            'dateScheduled',
            'dateExecuted',
            'dateFinished',
        ],
    ]); ?>

</div>