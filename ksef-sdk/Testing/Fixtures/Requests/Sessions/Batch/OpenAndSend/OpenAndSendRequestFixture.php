<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Sessions\Batch\OpenAndSend;

use Rcalicdan\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\AbstractFakturaFixture;
use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;

class OpenAndSendRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'formCode' => 'FA (3)',
        'faktury' => [],
        'offlineMode' => false,
    ];

    /**
     * @param array<int, AbstractFakturaFixture> $faktury
     */
    public function withFakturaFixtures(array $faktury): self
    {
        $this->data['faktury'] = array_map(fn (AbstractFakturaFixture $faktura) => $faktura->data, $faktury);

        return $this;
    }
}
