<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions;

use Rcalicdan\KSEFClient\Contracts\EnumInterface;

enum ProceduraZ: string implements EnumInterface
{
    case WstoEe = 'WSTO_EE';

    case Ied = 'IED';

    case TtD = 'TT_D';

    case BSpv = 'B_SPV';

    case BSpvDostawa = 'B_SPV_DOSTAWA';

    case BMpvProwizja = 'B_MPV_PROWIZJA';
}
