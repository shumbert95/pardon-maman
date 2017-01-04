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
     * @var int
     *
     * @ORM\Column(name="id_contest", type="integer")
     */
    private $idContest;

    /**
     * @var int
     *
     * @ORM\Column(name="id_participant", type="integer")
     */
    private $idParticipant;

    /**
     * @var int
     *
     * @ORM\Column(name="id_photo", type="integer")
     */
    private $idPhoto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @var int
     *
     * @ORM\Column(name="active", type="integer")
     */
    private $active;


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
     * Set idContest
     *
     * @param integer $idContest
     *
     * @return ContestParticipant
     */
    public function setIdContest($idContest)
    {
        $this->idContest = $idContest;

        return $this;
    }

    /**
     * Get idContest
     *
     * @return int
     */
    public function getIdContest()
    {
        return $this->idContest;
    }

    /**
     * Set idParticipant
     *
     * @param integer $idParticipant
     *
     * @return ContestParticipant
     */
    public function setIdParticipant($idParticipant)
    {
        $this->idParticipant = $idParticipant;

        return $this;
    }

    /**
     * Get idParticipant
     *
     * @return int
     */
    public function getIdParticipant()
    {
        return $this->idParticipant;
    }

    /**
     * Set idPhoto
     *
     * @param integer $idPhoto
     *
     * @return ContestParticipant
     */
    public function setIdPhoto($idPhoto)
    {
        $this->idPhoto = $idPhoto;

        return $this;
    }

    /**
     * Get idPhoto
     *
     * @return int
     */
    public function getIdPhoto()
    {
        return $this->idPhoto;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return ContestParticipant
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

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return ContestParticipant
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return ContestParticipant
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }
}

