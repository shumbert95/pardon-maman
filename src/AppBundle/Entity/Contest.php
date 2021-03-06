<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contest
 *
 * @ORM\Table(name="contest")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContestRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Contest
{

    const COLOR_RED = 1;
    const COLOR_ORANGE = 2;
    const COLOR_BLUE = 3;
    const COLOR_GREEN = 4;
    const COLOR_YELLOW = 5;
    const COLOR_BLACK = 6;
    const COLOR_GREY = 7;

    static $colorLabels = [
        'Rouge' => self::COLOR_RED,
        'Orange' => self::COLOR_ORANGE,
        'Bleu' => self::COLOR_BLUE,
        'Vert' => self::COLOR_GREEN,
        'Jaune' => self::COLOR_YELLOW,
        'Noir' => self::COLOR_BLACK,
        'Gris' => self::COLOR_GREY
    ];

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
    private $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="date")
     */
    private $dateEnd;
    /**
     *
     * @ORM\ManyToOne(targetEntity="ContestParticipant", inversedBy="contests", cascade={"merge", "persist"})
     */
    private $winner;

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
     * @ORM\ManyToOne(targetEntity="Prize", inversedBy="contests", cascade={"merge", "persist"})
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

    /**
     * @var integer
     *
     * @ORM\Column(name="principal_color", type="integer", options={"default": 6})
     */
    private $principalColor;

    /**
     * @var integer
     *
     * @ORM\Column(name="secondary_color", type="integer", options={"default": 2})
     */
    private $secondaryColor;


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
        $this->dateEnd = $date_end;
        return $this;
    }

    /**
     * Get date_add
     *
     */

    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set data_start
     *
     *
     * @return Contest
     */
    public function setDateStart($date_start)
    {
        $this->dateStart = $date_start;
        return $this;
    }

    /**
     * Get date_start
     *
     */

    public function getDateStart()
    {
        return $this->dateStart;
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
        return $this->date_update;
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
     * Set winner
     *
     * @param ContestParticipant $winner
     *
     * @return Contest
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
        return $this;
    }

    /**
     * Get winner
     *
     * @return ContestParticipant
     */

    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Set template
     *
     * @param Template $template
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
    public function addRule(\AppBundle\Entity\Rule $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Remove rules
     *
     * @param \AppBundle\Entity\Rule $rule
     */
    public function removeRule(\AppBundle\Entity\Rule $rule)
    {
        $this->rules->removeElement($rule);
    }


    public function __toString() {
        return $this->name;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->date_add = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->date_update = new \DateTime();
    }

    public function getPrincipalColor()
    {
        return $this->principalColor;
    }

    public function setPrincipalColor($color)
    {
        $this->principalColor = $color;
        return $this;
    }

    public function getSecondaryColor()
    {
        return $this->secondaryColor;
    }

    public function setSecondaryColor($color)
    {
        $this->secondaryColor = $color;
        return $this;
    }


}

