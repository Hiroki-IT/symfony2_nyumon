<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#Doctrineによるマッピングは、本来『@Table()』である。
#しかし、SymfonyによるValidationのアノテーション（@NotBlank()）と区別するために、『as』で名前を付けている。

/**
 * BlogArticle
 *
 * #blog_articleテーブルとマッピング
 * @ORM\Table(name="blog_article")
 *
 * #対応するRepositoryを指定
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlogArticleRepository") #EntityファイルのRepository変数="Repositoryパス"で、Entityに対応するRepositoryを設定。
 */
class BlogArticle
{
    /**
     * #変数の型を定義
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * #変数の型を宣言
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="targetDate", type="date")
     */
    private $targetDate;

    /**
     * #変数の型を宣言
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;


    /**
     * Get id
     *
     * #返り値の型を宣言
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return BlogArticle
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set targetDate
     *
     * @param \DateTime $targetDate
     *
     * @return BlogArticle
     */
    public function setTargetDate($targetDate)
    {
        $this->targetDate = $targetDate;

        return $this;
    }

    /**
     * Get targetDate
     *
     * @return \DateTime
     */
    public function getTargetDate()
    {
        return $this->targetDate;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return BlogArticle
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}

