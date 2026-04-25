<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Send;

use Rcalicdan\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\AbstractFakturaFixture;
use Rcalicdan\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaRR\AbstractFakturaFixture as AbstractFakturaRRFixture;
use Rcalicdan\KSEFClient\Testing\Fixtures\Requests\AbstractRequestFixture;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\FormCode;

class SendRequestFixture extends AbstractRequestFixture
{
    /**
     * @var array<string, mixed>
     */
    public array $data = [
        'referenceNumber' => '20250625-EE-319D7EE000-B67F415CDC-2C',
    ];

    public function withFakturaFixture(AbstractFakturaFixture | AbstractFakturaRRFixture $faktura): self
    {
        $this->data['faktura'] = $faktura->data;

        return $this;
    }

    public function withFormCode(FormCode $formCode): self
    {
        $this->data['formCode'] = $formCode->value;

        return $this;
    }
}
