<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Message $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Отправить', ['send', 'id' => $model->id], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => 'Отправить сообщение?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Действительно хотитеть удалить сообщение?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'text',
            [
                'attribute' => 'status',
                'value' => function (\app\models\Message $message) {
                    return $message->getStatusName();
                }
            ],
            [
                'attribute' => 'photo',
                'format' => 'raw',
                'value' => function (\app\models\Message $message) {
                    if ($message->getImagePath() === null) {
                        return null;
                    }

                    return Html::img($message->getImagePath(), ['alt' => $message->name, 'style' => 'max-width: 150px']);
                }
            ],
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>
