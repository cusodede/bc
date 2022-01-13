# Как это работает?

* Добавляем трейт в класс, в котором реализация отправки запроса на внешний сервис

```php
	use TracerTrait;
```

* Используем методы трейта addTracer() и log(). Первый парсит headers и создает новые значения для trace-id и parent-id
  если это необходимо, лог запишет в лог ответ от внешнего сервиса.

```php
	self::addTracer($request);
	$response = $request->send();
	self::log($response);
```

## Настройка

* добавляем для компонента response обработчик события beforeSend. В котором добавляем header X-Trace-ID(trace-id). С
  этим id можем найти все запросы клиента по логам (как в локальной системе, так и во внешней)

```php
	'response' => [
		'class' => Response::class,
		'on beforeSend' => static function($event) {
			$tracer = GlobalTracer::get();
			if ($tracer instanceof TracerImplementation) {
				$span = $tracer->getSpan();
				if ($span) {
					Yii::$app->response->headers->set('X-Trace-ID', $span->getContext()->getTraceId());
				}
			}
		},
	],
```

* Для логирования используем компонент log Yii. Пример его настройки:

```php
	[
		'class' => FileTarget::class,
		'levels' => ['info'],
		'categories' => ['service.response'],
		'logFile' => '@runtime/logs/service_response.log',
		'maxFileSize' => 10240,
		'logVars' => []
	],
```

## Расшифровка лога

* Пример лога:

```php
2021-08-06 16:11:00 [127.0.0.1][-][-][info][service.response] jcsm6271lx4ezguna9t5ibwhqk0odyfp-0000000000000000-yeufjm7iodkq5ahc-recogDol 200 {"is_success":true,"error":""}
```

Timestamp [IP address][User ID][Session ID][Severity Level][Category] traceId-parentId-spanId-operationName status_code
data

- traceId - id запроса для его идентификации во всех сервисов
- parentId - spanId сервиса от которого запрос пришел. Если мы первый сервис в цепочке, то parentId равно
  0000000000000000
- spanId - id для определения сервиса
- operationName - название операции в локальном сервисе
