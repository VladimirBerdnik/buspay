<?php

namespace App\Domain\Exceptions\Constraint;

use App\Models\Company;

/**
 * Thrown when company cannot be deleted due to related records restrictions.
 */
class CompanyDeletionException extends BusinessLogicConstraintException
{
    /**
     * Company that can't be deleted.
     *
     * @var Company
     */
    private $company;

    /**
     * Thrown when company cannot be deleted due to related records restrictions.
     *
     * @param Company $company Company that can't be deleted
     */
    public function __construct(Company $company)
    {
        parent::__construct('Company with related records can not be deleted');
        $this->company = $company;
    }

    /**
     * Returns company that can't be deleted.
     *
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }
}
