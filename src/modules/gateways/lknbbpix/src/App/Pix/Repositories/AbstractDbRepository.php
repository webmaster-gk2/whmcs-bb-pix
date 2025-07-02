<?php

namespace Lkn\BBPix\App\Pix\Repositories;

use Illuminate\Database\Query\Builder;
use WHMCS\Database\Capsule;

/**
 * @since 1.2.0
 */
abstract class AbstractDbRepository
{
    /**
     * @since 1.2.0
     * @var string
     */
    protected string $table;

    protected function query(): Builder
    {
        return Capsule::table($this->table);
    }
}
