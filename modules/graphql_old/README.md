# Документация по GraphQL модулю

``` bash
.
├── base # Базовые класы мутаций и запросов
├── controllers
├── data # Типы данных которые мы создали Enum, Errors, Mutation, Query, Response ...
└── schema # GraphQL схемы
    ├── common # Базовые схемы для мутаций и запросов
    ├── mutations # Мутации
    │   ├── MutationType.php # Базовая схема
    │   └── extended # Все наши схемы для мутаций моделей, партнёры, продукты ...
    └── query # Запросы на получение данных
        ├── QueryType.php # Базовая схема запросов
        └── extended Все наши схемы для получения данных моделей, партнёры, продукты ...
            └── enum # Отдельно Выделил схемы для перечислений, так как они не модели
```

### queries

```code json
query {
    examples {
        id,
        username
    },
    example(id: 10) {
        id,
        username
    }
}
```

=>

```
{
    "data": {
        "examples": [
            {
                "id": 8,
                "username": "hello8"
            },
            {
                "id": 13,
                "username": "hello13"
            }
        ],
        "example": {
            "id": 10,
            "username": "hello5"
        }
    }
}
```

### mutations

1. create

```code
mutation {
    example {
        create(username: "hello25") {
            ...on Response{
                result,
                message,
                errors{field, messages}
            }
        }
    }
}
```

=>

```code
{
    "data": {
        "example": {
            "create": {
                "result": true,
                "message": "Партнер успешно сохранен",
                "errors": []
            }
        }
    }
}
```

2. update

```code
mutation {
    example(id: 10) {
        update(username: "hello25") {
            ...on Response{
                result,
                message,
                errors{field, messages}
            }
        }
    }
}
```

=>

```code
{
    "data": {
        "example": {
            "update": {
                "result": false,
                "message": "Ошибка сохранения партнера",
                "errors": [
                    {
                        "field": "username",
                        "messages": [
                            "Пример ответа с ошибкой в поле"
                        ]
                    }
                ]
            }
        }
    }
}
```
