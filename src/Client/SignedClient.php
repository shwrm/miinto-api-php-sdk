<?php declare(strict_types=1);

namespace Shwrm\Miinto\Client;

use GuzzleHttp\Psr7\Request;
use Shwrm\Miinto\Utils\RequestSigner;
use Shwrm\Miinto\ValueObject\MiintoCommunicationChannel;

abstract class SignedClient extends BasicClient
{
    public final function doRequest(Request $request): string
    {
        $mcc     = $this->getMiintoCommunicationChannel();
        $request = $this->getRequestSigner()->sign($request, $mcc);

        return parent::doRequest($request);
    }

    abstract protected function getMiintoCommunicationChannel(): MiintoCommunicationChannel;

    abstract protected function getRequestSigner(): RequestSigner;
}
