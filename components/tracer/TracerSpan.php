<?php

declare(strict_types = 1);

namespace app\components\tracer;

use OpenTracing\Span;
use OpenTracing\SpanContext;

/**
 * Class TracerSpan
 */
class TracerSpan implements Span {
	/** @var string */
	private string $operationName;

	/** @var string */
	private string $parentId;

	/**
	 * @var SpanContext
	 */
	private SpanContext $context;

    /**
     * @param string $operationName
     * @param SpanContext $context
     * @param string $parentId
     */
    public function __construct(string $operationName, SpanContext $context, string $parentId) {
		$this->operationName = $operationName;
		$this->context = $context;
		$this->parentId = $parentId;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getOperationName():string {
		return $this->operationName;
	}

	/**
	 * @return string
	 */
	public function getParentId():string {
		return $this->parentId;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContext():SpanContext {
		return $this->context;
	}

	/**
	 * {@inheritdoc}
	 */
	public function finish($finishTime = null):void {
	}

	/**
	 * {@inheritdoc}
	 */
	public function overwriteOperationName(string $newOperationName):void {
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTag(string $key, $value):void {
	}

	/**
	 * {@inheritdoc}
	 */
	public function log(array $fields = [], $timestamp = null):void {
	}

	/**
	 * {@inheritdoc}
	 */
	public function addBaggageItem(string $key, string $value):void {
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBaggageItem(string $key):?string {
		return null;
	}
}
