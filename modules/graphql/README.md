### queries

```code
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
                "result": true,
                "message": "Партнер успешно сохранен",
                "errors": []
            }
        }
    }
}
```
