<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JSON; //シリアライズ：オブジェクトをJSONやXMLなどのファイル化可能なフォーマットに変換すること

/**
 * Concert
 *
 * #concertテーブルとマッピング
 * @ORM\Table(name="concert")
 *
 * #対応するRepositoryを指定
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConcertRepository")
 *
 * #明示的に公開するように設定したプロパティ以外はシリアライズしない
 * @JSON\ExclusionPolicy("all")
 */
class Concert
{
    /**
     *
     * #JSON形式に変換させない
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     *
     * #Expose()でJSON形式に変換することを宣言
     * @JSON\Expose()
     *
     * #JSONに出力するときのフォーマット
     * @JSON\Type("DateTime<'Y-m-d'>")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="time")
     * @JSON\Expose()
     * @JSON\Type("DateTime<'H:i'>")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="place", type="string", length=100)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $place;

    /**
     * @var bool
     *
     * @ORM\Column(name="available", type="boolean")
     * @JSON\Expose()
     * @JSON\Type("boolean")
     */
    private $available;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Concert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Concert
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set place
     *
     * @param string $place
     *
     * @return Concert
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set available
     *
     * @param boolean $available
     *
     * @return Concert
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return bool
     */
    public function getAvailable()
    {
        return $this->available;
    }
}

