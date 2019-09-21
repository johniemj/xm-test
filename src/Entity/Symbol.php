<?php 
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Symbol
{
  /**
   * @Assert\NotBlank
   */
  protected $companySymbol;
  /**
   * @Assert\NotBlank
   * @Assert\Date
   * @var string A "Y-m-d" formatted value
   */
  protected $startDate;
  /**
   * @Assert\NotBlank
   * @Assert\Date
   * Assert\Callback(methods={"validateEndDate"})
   * @var string A "Y-m-d" formatted value
   */
  protected $endDate;
  /**
   * @Assert\NotBlank
   * @Assert\Email(
   *     message = "The email '{{ value }}' is not a valid email.",     
   * )
   */
  protected $email;

  public function setCompanySymbol($companySymbol)
  {
    $this->companySymbol = $companySymbol;
  }

  public function setStartDate($startDate)
  {
    $this->startDate = $startDate;
  }

  public function setEndDate($endDate)
  {
    $this->endDate = $endDate;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function getCompanySymbol()
  {
    return $this->companySymbol;
  }

  public function getStartDate()
  {
    return $this->startDate;
  }

  public function getEndDate()
  {
    return $this->endDate;
  }

  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @Assert\Callback
   */
  public function validateEndDate(ExecutionContextInterface $context, $payload) 
  {
    if ($this->endDate < $this->startDate) 
    {
        $context->buildViolation('End date must be later than start date')
            ->atPath('endDate')
            ->addViolation();
    }
  }
}