<?php declare(strict_types=1);

namespace Shwrm\Miinto\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Shwrm\Miinto\Utils\RequestSigner;
use Shwrm\Miinto\ValueObject\MiintoCommunicationChannel;

class PermaChannelClient extends BasicClient
{
    /** @var RequestSigner */
    private $requestSigner;

    /** @var MiintoCommunicationChannel */
    private $mcc;

    public function __construct(Client $client, MiintoCommunicationChannel $mcc, string $baseUri, RequestSigner $requestSigner = null)
    {
        $this->mcc           = $mcc;
        $this->requestSigner = $requestSigner ?? new RequestSigner();

        parent::__construct($client, $baseUri);
    }

    public function doRequest(Request $request): string
    {
        $request = $this->requestSigner->sign($request, $this->mcc);

        return parent::doRequest($request);
    }
}
