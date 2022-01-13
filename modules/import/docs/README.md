## Как это работает:

Через веб-интерфейс загружаем файл. Урл такие:

- /import/import-xls/dealers
- /import/import-xls/branches
- /import/import-xls/selling-channels
- /import/import-xls/update-sellers-status
- /import/import-xls/create-reward-rules (временно)

После загрузки создается таска на обработку файла. Выводим ошибки в лог.

Для того, чтобы создать свой загрузчик нужно:

- добавить action в контроллер
- добавить в конфиг console.php в components.log.target в соответствующий logger свою категорию
- добавить свой обработчик в modules/import_common/models/jobs
- файлы загружаем через FileStorage, для того, чтобы загрузить файл через него нужно добавить FileStorageTrait в класс для которого пишем
  загрузчик, и туда же добавить свойство $loadXls. Например:

```php
class Sellers extends SellersAR {
	use FileStorageTrait;

	public mixed $loadXls = null;
```

- в обработчике можно прописать такие константы, чтобы можно было использовать некоторые хелперы. Например Helper::
  checkHeader().

```php
	private const COLUMN_NUM = 3; // сколько колонок в файле

	private const NAME = 0; # индекс первой колонки
	private const CODE = 1; # индекс второй колонки
	private const FILE_SCHEMA = [ # схема файла
		self::NAME => 'Филиал',
		self::CODE => 'Код',
	];
```

## Подключение

- Данные загружаются через веб-интерфейс, а обрабатываются тасками, поэтому в web.php и console.php прописываем:

```php
'import' => [
    'class' => ImportCommonModule::class
]
```

- Добавить в console.php логгер:

```php
[
    'class' => FileTarget::class,
    'categories' => ['import.branches', 'import.dealers', 'import.channel', 'import.update_seller_status'],
    'levels' => ['info', 'error'],
    'logFile' => '@runtime/logs/import_main/import.log',
    'maxFileSize' => 10240,
    'logVars' => []
],
```

