<?php

namespace App\Domain;

use App\Models\Company;

/**
 * Model that belongs to company.
 *
 * @property integer|null $company_id Company identifier for which this company belongs to
 *
 * @property-read Company|null $company Company for which this model belongs to
 */
interface IBelongsToCompany
{
}
