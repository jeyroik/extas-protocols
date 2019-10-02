<?php
namespace extas\components\protocols;

use Psr\Http\Message\RequestInterface;

/**
 * Class ProtocolParameterHeaderDefault
 *
 * @package extas\components\protocols
 * @author jeyroik@gmail.com
 */
class ProtocolParameterHeaderDefault extends Protocol
{
    protected $protocolKey = '';

    const HEADER__PREFIX = 'x-extas-';

    /**
     * @param array $args
     * @param RequestInterface $request
     */
    public function __invoke(array &$args = [], RequestInterface $request)
    {
        $fromHeader = $this->grabHeaders($request);
        $fromParameter = $this->grabParameters($request);
        $default = $this->getDefault();

        if (!isset($args[$this->protocolKey])) {
            $args[$this->protocolKey] = is_null($fromParameter)
                ? (is_null($fromHeader)
                    ? $default
                    : $fromHeader)
                : $fromParameter;
        }
    }

    /**
     * @return string
     */
    protected function getDefault(): string
    {
        return getenv('EXTAS__PROTOCOL_' . strtoupper($this->protocolKey) . '__DEFAULT') ?: '';
    }

    /**
     * @param RequestInterface $request
     *
     * @return null|string
     */
    protected function grabHeaders(RequestInterface $request): ?string
    {
        $headerPrefix = getenv('EXTAS__PROTOCOL_' . strtoupper($this->protocolKey) . '__HEADER_PREFIX')
            ?: static::HEADER__PREFIX;

        $headerName = $headerPrefix . $this->protocolKey;
        $headers = $request->getHeader($headerName);
        if (count($headers)) {
            return array_shift($headers);
        }

        return null;
    }

    /**
     * @param RequestInterface $request
     *
     * @return null|string
     */
    protected function grabParameters(RequestInterface $request): ?string
    {
        parse_str($request->getUri()->getQuery(), $queryParams);

        return $queryParams[$this->protocolKey] ?? null;
    }
}
