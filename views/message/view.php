<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Message $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
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
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                'attribute' => 'files',
                'format' => 'raw',
                'value' => function (\app\models\Message $message) {
                    $files = $message->getFilesInfo();

                    if ($files === null) {
                        return null;
                    }

                    foreach ($files as $file) {
                        $result[] = Html::a($file['url'], $file['url'], ['target' => '_blank']);
                    }

                    return isset($result) ? join('<br>', $result) : '';
                }
            ],
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>
