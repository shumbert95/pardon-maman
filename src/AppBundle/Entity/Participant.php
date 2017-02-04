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


    public function __construct() {
        $this->stayOptions = new ArrayCollection();
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
}
