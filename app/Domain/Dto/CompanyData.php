<?php

namespace App\Domain\Dto;

use Saritasa\Dto;

/**
 * Company details.
 *
 * @property-read string $name Company name
 * @property-read string $account_number Account number for payments
 * @property-read string $contact_information Company contact information
 * @property-read string $bin Business identification number
 */
class CompanyData extends Dto
{
    public const NAME = 'name';
    public const ACCOUNT_NUMBER = 'account_number';
    public const CONTACT_INFORMATION = 'contact_information';
    public const BIN = 'bin';

    /**
     * Company name.
     *
     * @var string
     */
    protected $name;

    /**
     * Account number for payments.
     *
     * @var string
     */
    protected $account_number;

    /**
     * Company contact information.
     *
     * @var string
     */
    protected $contact_information;

    /**
     * Business identification number.
     *
     * @var string
     */
    protected $bin;
}
