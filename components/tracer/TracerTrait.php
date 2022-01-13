<?php
declare(strict_types = 1);

namespace app\components\tracer;

use app\components\logstash\LogStash;
use OpenTracing\GlobalTracer;
use Yii;
use yii\httpclient\Request;
use yii\httpclient\Response;
use const OpenTracing\Formats\HTTP_HEADERS;

/**
 * Trace TracerTrait
 */
trait TracerTrait {
	/**
	 * @param Request $request
	 * @param string $operationName говорит нам какую операцию логируем
	 */
	public static function addTracer(Request $request, string $operationName):void {
		# Создаем singleton для нашей имплементации
		GlobalTracer::set(new TracerImplementation());

		# Получаем header traceparent (может быть пустое)
		$spanContext = GlobalTracer::get()->extract(
			HTTP_HEADERS,
			Yii::$app->request->headers??[]
		);

		# Создаем новый header traceparent
		$span = GlobalTracer::get()->startSpan($operationName, ['childOf' => $spanContext]);

		# Добавляем traceparent в header выходного запроса
		GlobalTracer::get()->inject(
			$span->getContext(),
			HTTP_HEADERS,
			$request->headers
		);
	}

	/**
	 * @param Response $response
	 */
	public static function log(Response $response):void {
		/** @var  TracerImplementation $tracer */
		$tracer = GlobalTracer::get();
		$span = $tracer->getSpan();

		/** @var  TracerSpanContext $context */
		$context = $span->getContext();

		$requestId = implode(
			'-',
			[$context->getTraceId(), $span->getParentId(), $context->getSpanId(), $span->getOperationName()]
		);

		Yii::info(
			LogStash::create_log(
				"{$response->statusCode} {$response->content}",
				['traceId' => $requestId]
			),
			'service.response'
		);
	}
}