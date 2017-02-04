<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contest
 *
 * @ORM\Table(name="contest")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContestRepository")
 */
class Contest
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="date")
     */
    private $date_start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="date")
     */
    private $date_end;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_winner", type="integer", nullable=true)
     */
    private $id_winner;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Template", inversedBy="reservations", cascade={"merge", "persist"})
     */
    private $template;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Rule", cascade={"persist"})
     * @ORM\JoinTable(name="contest_rule",
     *      joinColumns={@ORM\JoinColumn(name="contest_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="rule_id", referencedColumnName="id")}
     * )
     */
    private $rules;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Prize", inversedBy="reservations", cascade={"merge", "persist"})
     */
    private $prize;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $date_add;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $date_update;

    /**
     * @ORM\OneToMany(targetEntity="ContestParticipant", mappedBy="contest", cascade={"all"}, fetch="EAGER")
     */
    private $contestParticipants;


    /**
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    private $participants;


    public function __construct()
    {
        $this->contestParticipants = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Contest
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     *
     * @return Contest
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set data_add
     *
     *
     * @return Contest
     */
    public function setDateAdd($date_add)
    {
        $this->date_add = $date_add;
        return $this;
    }

    /**
     * Get date_add
     *
     */

    public function getDateAdd()
    {
        return $this->date_add;
    }

    /**
     * Set data_end
     *
     *
     * @return Contest
     */
    public function setDateEnd($date_end)
    {
        $this->date_end = $date_end;
        return $this;
    }

    /**
     * Get date_add
     *
     */

    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * Set data_start
     *
     *
     * @return Contest
     */
    public function setDateStart($date_start)
    {
        $this->date_start = $date_start;
        return $this;
    }

    /**
     * Get date_start
     *
     */

    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * Set data_update
     *
     *
     * @return Contest
     */
    public function setDateUpdate($date_update)
    {
        $this->date_update = $date_update;
        return $this;
    }

    /**
     * Get date_update
     *
     */

    public function getDateUpdate()
    {
        return $this->date_start;
    }


    /**
     * Set status
     *
     * @param string $status
     *
     * @return Contest
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
     * Set prize
     *
     * @param Prize $prize
     *
     * @return Contest
     */
    public function setPrize($prize)
    {
        $this->prize = $prize;
        return $this;
    }

    /**
     * Get prize
     *
     * @return Prize
     */

    public function getPrize()
    {
        return $this->prize;
    }

    /**
     * Set template
     *
     * @param Prize $template
     *
     * @return Contest
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Get template
     *
     * @return Template
     */

    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param ContestParticipant $contestParticipant
     */
    public function addContestParticipant($contestParticipant)
    {
        $this->contestParticipants->add($contestParticipant);
    }

    /**
     *
     * @param ContestParticipant $contestParticipant
     */
    public function removeContestParticipant($contestParticipant)
    {
        $this->contestParticipants->removeElement($contestParticipant);
    }

    /**
     *
     * @param Collection <ContestParticipant> $contestPArticipants
     * @return \AppBundle\Entity\Contest
     */
    public function setContestParticipants($contestParticipants)
    {
        $this->contestParticipants = $contestParticipants;

        return $this;
    }

    /**
     * @return Collection <ContestParticipant>
     */
    public function getContestParticipants()
    {
        return $this->contestParticipants;
    }

    public function getParticipants()
    {
        $participants = new ArrayCollection();
        foreach ($this->contestParticipants as $contestParticipant) {
            $participants->add($contestParticipant->getParticipant());
        }
        return $participants;
    }

    /**
     * Set rules
     *
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Get rules
     *
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Add rules
     *
     * @param \AppBundle\Entity\Rule $rule
     * @return Contest
     */
    public function addPrize(\AppBundle\Entity\Rule $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Remove rules
     *
     * @param \AppBundle\Entity\Prize $rule
     */
    public function removeRule(\AppBundle\Entity\Rule $rule)
    {
        $this->rules->removeElement($rule);
    }


    public function __toString() {
        return $this->name;
    }
}

