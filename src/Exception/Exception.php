<?php declare(strict_types=1);

namespace Shwrm\Miinto\Exception;

use Psr\Http\Message\RequestInterface;

abstract class Exception extends \Exception
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(string $message, RequestInterface $request, \Throwable $previous = null)
    {
        parent::__construct($message, $previous->getCode() ?? 0, $previous);

        $this->request = $request;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
