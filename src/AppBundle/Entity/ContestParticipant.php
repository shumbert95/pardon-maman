<?php

namespace AppBundle\Entity;

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
}

