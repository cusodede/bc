<?php
declare(strict_types = 1);

namespace app\components\tracer;

use app\components\helpers\ArrayHelper;
use app\components\ProjectConstants;
use OpenTracing\NoopScope;
use OpenTracing\NoopScopeManager;
use OpenTracing\Scope;
use OpenTracing\ScopeManager;
use OpenTracing\Span;
use OpenTracing\SpanContext;
use OpenTracing\Tracer;

/**
 * Class TracerImplementation
 */
class TracerImplementation implements Tracer {
	/**
	 * @var TracerSpan|null
	 */
	private ?TracerSpan $span = null;

	/**
	 * {@inheritdoc}
	 * @noinspection ParameterDefaultValueIsNotNullInspection - требование при наследовании
	 */
	public function startActiveSpan(string $operationName, $options = []):Scope {
		return new NoopScope();
	}

	/**
	 * {@inheritdoc}
	 * @noinspection ParameterDefaultValueIsNotNullInspection - требование при наследовании
	 */
	public function startSpan(string $operationName, $options = []):Span {
		if (isset($options['childOf']) && $options['childOf']->getTraceId()) {
			$parentId = $options['childOf']->getSpanId();
			$spanContext = new TracerSpanContext(
				$options['childOf']->getTraceId(),
				$this->generateRandomString(16),
			);
		} else {
			$parentId = str_pad('', 16, '0', STR_PAD_LEFT);
			$spanContext = new TracerSpanContext(
				$this->generateRandomString(),
				$this->generateRandomString(16)
			);
		}

		$this->span = new TracerSpan($operationName, $spanContext, $parentId);
		return $this->span;
	}

	/**
	 * @param int $length
	 * @return string
	 */
	private function generateRandomString(int $length = 32):string {
		return substr(str_shuffle(ProjectConstants::DIGITS.ProjectConstants::LOWERCASE_CHARACTERS), 1, $length);
	}

	/**
	 * {@inheritdoc}
	 */
	public function inject(SpanContext $spanContext, string $format, &$carrier):void {
		/** @var  TracerSpanContext $spanContext */
		$carrier['Traceparent'] = "00-{$spanContext->getTraceId()}-{$spanContext->getSpanId()}-00";
	}

	/**
	 * {@inheritdoc}
	 */
	public function extract(string $format, $carrier):?SpanContext {
		/** @var string $traceParent */
		$traceParent = ArrayHelper::getValue($carrier, 'Traceparent');
		if ($traceParent) {
			$traceParentArray = explode('-', $traceParent);
			if (!empty($traceParentArray) && isset($traceParentArray[1], $traceParentArray[2])) {
				return new TracerSpanContext($traceParentArray[1], $traceParentArray[2]);
			}
		}
		return null;
	}

	/**
	 * @return TracerSpan
	 */
	public function getSpan():TracerSpan {
		return $this->span;
	}

	/**
	 * {@inheritdoc}
	 */
	public function flush():void {
	}

	/**
	 * {@inheritdoc}
	 */
	public function getScopeManager():ScopeManager {
		return new NoopScopeManager();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getActiveSpan():?Span {
		return null;
	}
}