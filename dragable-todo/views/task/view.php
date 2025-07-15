<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
/** @var bool $isAll */

$this->title = $model->title;;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Tasks'),
    'url' => $isAll ? ['index-all'] : ['index'],
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="task-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id, 'isAll' => $isAll], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    $attributes = [
        'id',
        'title',
        'description:ntext',
        'status',
        'created_at',
    ];

    if (!empty($isAll)) {
        $attributes[] = 'user_id';
    }

    echo DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]);
    ?>

</div>
