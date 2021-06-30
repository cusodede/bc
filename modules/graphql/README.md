# Документация по GraphQL модулю

``` bash
.
├── GraphqlModule.php
├── README.md
├── controllers
│   └── GraphqlController.php
└── schema # Схемы для GraphQL
    ├── common # Общие схемы для mutation и query
    │   ├── Response.php # Универсальная схема ответа для мутаций
    │   ├── Types.php # Все наши кастомные типы
    │   └── ValidationErrorType.php # Схема для ошибок валидации
    ├── mutations # Мутации
    │   ├── BaseMutationType.php # Абстрактная мутация 
    │   ├── MutationTrait.php # Расщирение для мутаций
    │   ├── MutationType.php # Базовая GraphQL схема для мутаций
    │   └── extended # Все наши кастомные схемы мутаций, по сути Models
    │       └── ...
    └── types # Запросы
        ├── QueryType.php # Базовая GraphQL схема для запросов
        ├── TypeTrait.php # Расширение для запросов
        └── extended # Все наши кастомные схемы запросов, по сути Models
            └── ...
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
