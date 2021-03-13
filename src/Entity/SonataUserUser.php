<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SonataUserUserRepository")
 * @ORM\Table(name="fos_user__user")
 */
class SonataUserUser extends BaseUser
{
    const ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
     */
    protected $orders;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="employee")
     */
    protected $employeeOrders;

    public function __construct() {
        parent::__construct();

        $this->orders = new ArrayCollection();
        $this->employeeOrders = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Order[]|Collection
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param Order $order
     */
    public function addOrder(Order $order): void
    {
        $this->orders[] = $order;
    }

    /**
     * @param Order $order
     */
    public function removeOrder(Order $order): void
    {
        if($this->orders->contains($order)){
            $this->orders->removeElement($order);
        }
    }

    /**
     * @return Order[]|Collection
     */
    public function getEmployeeOrders(): Collection
    {
        return $this->employeeOrders;
    }

    /**
     * @param Order $order
     */
    public function addEmployeeOrder(Order $order): void
    {
        $this->employeeOrders[] = $order;
    }

    /**
     * @param Order $order
     */
    public function removeEmployeeOrder(Order $order): void
    {
        if($this->employeeOrders->contains($order)){
            $this->employeeOrders->removeElement($order);
        }
    }
}