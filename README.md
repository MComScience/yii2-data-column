<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii2 Extension</h1>
    <br>
</p>

## Requirements

The minimum requirement is that your Web server supports PHP 7.0 or >.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```
composer require m-comscience/yii2-data-column
```

## Usage

Controller
```php
use mcomscience\data\DataColumn;
use mcomscience\data\ActionColumn;
use yii\data\ArrayDataProvider;

public function ActionDataServiceGroup()
{
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $query = (new \yii\db\Query())
        ->select([
            'tb_service_group.*',
        ])
        ->from('tb_service_group')
        ->all();
    $dataProvider = new ArrayDataProvider([
        'allModels' => $query,
    ]);
    // or
    /*
    use yii\data\ActiveDataProvider;

    $query = Post::find()->where(['status' => 1]);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
                'title' => SORT_ASC, 
            ]
        ],
    ]);
    */
    $columns = Yii::createObject([
        'class' => DataColumn::className(),
        'dataProvider' => $dataProvider,
        'formatter' => Yii::$app->formatter,
        'columns' => [
            [
                'attribute' => 'service_group_id',
            ],
            [
                'attribute' => 'service_group_name',
            ],
            [
                'attribute' => 'service_group_status',
            ],
            [
                'attribute' => 'service_group_status',
                'value' => function ($model, $key, $index) {
                    return static::getBadgeStatus($model['service_group_status']);
                },
                'format' => 'raw',
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete}',
                'viewOptions' => [
                    'title' => Yii::t('yii','View'),
                    //'label' => 'View'
                ],
                'updateOptions' => [
                    'role' => 'modal-remote',
                    'title' => Yii::t('yii','Edit'),
                ],
                'deleteOptions' => [
                    'class' => 'text-danger on-delete',
                    'title' => Yii::t('yii','Delete'),
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action == 'update') {
                        return Url::to(['/app/settings/update-service-group', 'id' => $key]);
                    }
                    if ($action == 'delete') {
                        return Url::to(['/app/settings/delete-service-group', 'id' => $key]);
                    }
                },
            ],
            /*
            DropdownButton
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {btn1}',
                'dropdown' => true,
                'dropdownButton' => [
                    'label' => 'Actions',
                    'class' => 'btn btn-success'
                ],
                'viewOptions' => [
                    'role' => 'modal-remote',
                    'title' => 'Detail',
                    'label' => 'Detail',
                ],
                'buttons' => [
                    'btn1' => function ($url, $model, $key) {
                        return Html::tag('li', Html::a('Add', $url, ['data-pjax' => 0]));
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action == 'view') {
                        return Url::to(['view', 'id' => $key]);
                    }
                },
            ],
            */
        ],
    ]);
    return $columns->renderDataColumns();
}
```
