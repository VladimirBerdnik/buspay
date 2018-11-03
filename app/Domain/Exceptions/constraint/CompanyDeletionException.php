<?php

namespace App\Domain\Exceptions\Constraint;

use App\Models\Company;

/**
 * Thrown when company can not be deleted due to related records restrictions.
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
     * Thrown when company can not be deleted due to related records restrictions.
     *
     * @param Company $company Company that can't be deleted
     */
    public function __construct(Company $company)
    {
        parent::__construct('Компания не может быть удалена, есть подчиненные записи');
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
