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

    public function __construct() {
        parent::__construct();

        $this->orders = new ArrayCollection();
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
}