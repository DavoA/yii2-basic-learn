<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap5\LinkPager;
use kartik\daterange\DateRangePicker;

/** @var yii\web\View $this */
/** @var app\models\TaskSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var bool $isAll (optional) */

$columns = [
    ['class' => 'yii\grid\SerialColumn'],

    [
        'attribute' => 'title',
        'label' => Yii::t('app', 'Title'),
    ],
    [
        'attribute' => 'description',
        'label' => Yii::t('app', 'Description'),
        'format' => 'ntext',
    ],
    [
        'attribute' => 'status',
        'label' => Yii::t('app', 'Status'),
        'value' => function($model) {
            return Html::tag('span', $model->status === 'completed' ? Yii::t('app', 'Completed') : Yii::t('app', 'Pending'), [
                'class' => 'status-label',
                'data-id' => $model->id,
            ]);
        },
        'format' => 'raw',
        'filter' => Html::activeDropDownList($searchModel, 'status', [
            'completed' => Yii::t('app', 'Completed'),
            'pending' => Yii::t('app', 'Pending'),
        ], ['class' => 'form-control', 'prompt' => Yii::t('app', 'All')]),
    ],
    [
        'attribute' => 'created_at',
        'label' => Yii::t('app', 'Created At'),
        'format' => 'datetime',
        'filter' => DateRangePicker::widget([
            'model' => $searchModel,
            'attribute' => 'date_range',
            'convertFormat' => true,
            'pluginOptions' => [
                'locale' => [
                    'format' => 'Y-m-d',
                    'separator' => ' to ',
                    'applyLabel' => Yii::t('app', 'Apply'),
                    'cancelLabel' => Yii::t('app', 'Cancel'),
                ],
                'opens' => 'right',
                'autoUpdateInput' => false,
                'showDropdowns' => true,
            ],
            'options' => [
                'id' => 'tasksearch-date_range',
                'autocomplete' => 'off',
                'class' => 'form-control',
                'placeholder' => Yii::t('app', 'Select date range'),
                'readonly' => true,
            ],
        ]),
    ],
];

if (!empty($isAll)) {
    $columns[] = [
        'attribute' => 'user_id',
        'label' => Yii::t('app', 'User'),
        'value' => function($model) {
            return $model->user ? Html::encode($model->user->username) : $model->user_id;
        },
        'format' => 'raw',
        'filter' => Html::activeDropDownList(
            $searchModel,
            'user_id',
            $usersList,
            ['class' => 'form-control', 'prompt' => Yii::t('app', 'All Users')]
        ),
    ];
}

$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'header' => Yii::t('app', 'Actions'),
    'template' => '{view} {update} {delete} {toggle}',
    'buttons' => [
        'toggle' => function ($url, $model) {
            return Html::button(Yii::t('app', 'Toggle Status'), [
                'class' => 'btn btn-sm btn-primary toggle-status',
                'data-id' => $model->id,
            ]);
        },
    ],
    'urlCreator' => function ($action, $model, $key, $index) use ($isAll) {
        $params = ['id' => $model->id];
        if (!empty($isAll)) {
            $params['isAll'] = $isAll;
        }
        return Url::to(array_merge([$action], $params));
    },
];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pager' => [
        'class' => LinkPager::class,
        'options' => ['class' => 'pagination justify-content-center'],
        'activePageCssClass' => 'active',
        'disabledPageCssClass' => 'disabled',
        'prevPageLabel' => '&laquo;',
        'nextPageLabel' => '&raquo;',
    ],
    'columns' => $columns,
]);
?>
