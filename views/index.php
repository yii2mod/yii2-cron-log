<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii2mod\cron\models\enumerables\CronScheduleStatus;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \yii2mod\cron\models\search\CronScheduleSearch */

$this->title = Yii::t('yii2mod-cron-log', 'Cron Schedule Log');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cron-schedule-model-index">

    <h1><?php echo Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['timeout' => 5000]); ?>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'jobCode',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return CronScheduleStatus::getLabel($model->status);
                },
                'filter' => CronScheduleStatus::listData(),
                'filterInputOptions' => ['prompt' => Yii::t('yii2mod-cron-log', 'Select Status'), 'class' => 'form-control'],
            ],
            'messages:ntext',
            'dateCreated',
            'dateFinished',
        ]
    ]); ?>
    <?php Pjax::end(); ?>
</div>