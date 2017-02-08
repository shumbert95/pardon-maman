<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContestParticipant
 *
 * @ORM\Table(name="contest_participant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContestParticipantRepository")
 */
class ContestParticipant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Contest", inversedBy="contestParticipants", cascade="persist")
     */
    private $contest;

    /**
     * @ORM\ManyToOne(targetEntity="Participant", inversedBy="contestParticipants", cascade="persist")
     */
    private $participant;

    /**
     * @ORM\ManyToOne(targetEntity="Photo", inversedBy="contestParticipants", cascade="persist")
     */
    private $photo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="datetime")
     */
    private $dateInscription;

    /**
     * @var integer
     *
     * @ORM\Column(name="votes", type="integer", options={"default": 0})
     */
    private $votes;


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="contest_participant_voter",
     *      joinColumns={@ORM\JoinColumn(name="contest_participant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $voters;

    public function __construct()
    {
        $this->voters = new ArrayCollection();
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
     * Set contest
     *
     * @param Contest $contest
     *
     * @return ContestParticipant
     */
    public function setContest($contest)
    {
        $this->contest = $contest;

        return $this;
    }

    /**
     * Get contest
     *
     * @return Contest
     */
    public function getContest()
    {
        return $this->contest;
    }

    /**
     * Set participant
     *
     * @param Participant $participant
     *
     * @return ContestParticipant
     */
    public function setParticipant($participant)
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * Get participant
     *
     * @return Participant
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Set photo
     *
     * @param Photo $photo
     *
     * @return ContestParticipant
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set dateInscription
     *
     * @param \DateTime $dateInscription
     *
     * @return ContestParticipant
     */
    public function setDateInscritpion($dateInscription)
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * Get dateInscription
     *
     * @return \DateTime
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * Set votes
     *
     * @param integer $votes
     *
     * @return ContestParticipant
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Get votes
     *
     * @return ContestParticipant
     */
    public function getVotes()
    {
        return $this->votes;
    }

    public function increaseVotes()
    {
        $this->votes = $this->votes+1;
        return $this;
    }

    public function decreaseVotes()
    {
        $this->votes = $this->votes-1;
        return $this;
    }

    /**
     * Set voters
     *
     */
    public function setVoters($voters)
    {
        $this->voters = $voters;
        return $this;
    }

    /**
     * Get voters
     *
     */
    public function getVoters()
    {
        return $this->voters;
    }

    /**
     * Add voters
     *
     * @param \AppBundle\Entity\User $voter
     * @return Contest
     */
    public function addVoter(\AppBundle\Entity\User $voter)
    {
        $this->voters[] = $voter;

        return $this;
    }

    /**
     * Remove voters
     *
     * @param \AppBundle\Entity\User $voter
     */
    public function removeVoter(\AppBundle\Entity\User $voter)
    {
        $this->voters->removeElement($voter);
    }
}

