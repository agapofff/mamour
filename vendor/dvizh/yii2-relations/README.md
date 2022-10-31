Yii2-relations
==========
Модуль дает возможность быстро добавить возможность связывать модели между собой. Пример использования: привязка похожих продуктов.

Установка
---------------------------------
Выполнить команду

```
php composer require dvizh/yii2-relations "@dev"
```

Или добавить в composer.json

```
"dvizh/yii2-relations": "@dev",
```

И выполнить

```
php composer update
```

Подключение и настройка
---------------------------------
В конфигурационный файл приложения добавить модуль relations
```php
    'modules' => [
        //..
        'relations' => [
            'class' => 'dvizh\relations\Module',
            'fields' => ['code'],
        ],
        //..
    ]
```

*fields - массив доп. полей (по умолчанию в окне выбора показываются только id и название)

Использование
---------------------------------
Связи хранятся в отдельном поле (TEXT) в виде сериализованного массива, поле нужно создать и добавить в модели. К модели, которая имплементирует \dvizh\relations\interfaces\Torelate и наследует AR, подключить поведение:

```php
    function behaviors()
    {
        return [
            'relations' => [
                'class' => 'dvizh\relations\behaviors\AttachRelations',
                'relatedModel' => 'common\models\Product',
                'inAttribute' => 'relations',
            ],
        ];
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function getId()
    {
        return $this->id;
    }
```

* inAttribute - название поля модели, где будут храниться связи
* relatedModel - модель, элементы которой нужно привязывать

Теперь привязанные модели будет возвращаеть метод $model->getRelations()->all().

Виджеты
---------------------------------
Выбор подключаемых моделей осуществляется через виджет:

```<?=\dvizh\relations\widgets\Constructor::widget(['model' => $model]);?>```

Его необходимо вызвать внутри формы редактирования вашей модели.
