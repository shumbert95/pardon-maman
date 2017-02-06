<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticipantRepository")
 * @ORM\Table(name="participant")
 */
class Participant
{

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="User")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Prize", cascade={"persist"})
     * @ORM\JoinTable(name="participant_prize",
     *      joinColumns={@ORM\JoinColumn(name="participant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="prize_id", referencedColumnName="id")}
     * )
     */
    private $prizes;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;


    public function __construct() {
        $this->prizes = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param User|null $user
     * @return \AppBundle\Entity\Participant
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get prizes
     *
     */
    public function getPrizes()
    {
        return $this->prizes;
    }

    /**
     * Add prizes
     *
     * @param \AppBundle\Entity\Prize $prize
     * @return Participant
     */
    public function addPrize(\AppBundle\Entity\Prize $prize)
    {
        $this->prizes[] = $prize;

        return $this;
    }

    /**
     * Remove prizes
     *
     * @param \AppBundle\Entity\Prize $prize
     */
    public function removePrize(\AppBundle\Entity\Prize $prize)
    {
        $this->prizes->removeElement($prize);
    }


    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Prize
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return Photo
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }
}
