<?php
declare(strict_types = 1);

namespace app\components\tracer;

use EmptyIterator;
use OpenTracing\SpanContext;

/**
 * Class TracerSpanContext
 */
class TracerSpanContext implements SpanContext {

	/** @var null|string */
	private ?string $traceId;

	/** @var null|string */
	private ?string $spanId;

	/**
	 * @param $traceId
	 * @param $spanId
	 */
	public function __construct($traceId, $spanId) {
		$this->traceId = $traceId;
		$this->spanId = $spanId;
	}

	/**
	 * @return null|string
	 */
	public function getTraceId():?string {
		return $this->traceId;
	}

	/**
	 * @return null|string
	 */
	public function getSpanId():?string {
		return $this->spanId;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator() {
		return new EmptyIterator();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBaggageItem(string $key):?string {
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withBaggageItem(string $key, string $value):SpanContext {
		return new self($this->traceId, $this->spanId);
	}
}
