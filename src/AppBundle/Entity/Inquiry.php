<?php

namespace AppBundle\Entity; #データベースからレコードを取り出すと、本ファイルのエンティティのインスタンスとして取り出される

use Doctrine\ORM\Mapping as ORM; #DocrtrineのMappingフォルダの使用を宣言
use Symfony\Component\Validator\Constraints As Assert; #Constraintsフォルダの使用を宣言

/**
 * Inquiry
 *
 * @ORM\Table(name="inquiry") #Tableファイルのクラス内のname変数=""で、テーブル名を設定。
 * @ORM\Entity(repositoryClass="AppBundle\Entity\InquiryRepository") #EntityファイルのRepository変数="Repositoryパス"で、Entityに対応するRepositoryを設定。
 */
class Inquiry 
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer") #Columnファイルのクラス内のname変数にテキスト、type変数に文字型を格納。
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30) #プロパティは、『名前』『データ型』『サイズ』の順に記述
     * @Assert\NotBlank() #空欄を禁止する
     * @Assert\Length(max=30) #文字数を制限
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     * @Assert\Email() #メールアドレス形式のチェック
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=20, nullable=true)
     * @Assert\Length(max=20)
     * @Assert\Regex(pattern="/^[0-9]+$/") #正規表現によるチェック
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var string
     * 
     * @ORM\Column(name="process_status", type="string", length=20)
     * @Assert\NotBlank(groups={"admin"}) #バリデーショングループadminを指定
     */
    private $processStatus;

    /**
     * @var string
     * 
     * @ORM\Column(name="process_memo", type="text")
     * @Assert\NotBlank(groups={"admin"}) #バリデーショングループadminを指定
     */
    private $processMemo;

    public function __construct()
    {
        $this->processStatus = 0;
        $this->processMemo = '';
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
     * @return Inquiry
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
     * Set email
     *
     * @param string $email
     *
     * @return Inquiry
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Inquiry
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Inquiry
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Inquiry
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

    /**
     * Set processStatus
     *
     * @param string $processStatus
     *
     * @return Inquiry
     */
    public function setProcessStatus($processStatus)
    {
        $this->processStatus = $processStatus;

        return $this;
    }

    /**
     * Get processStatus
     *
     * @return string
     */
    public function getProcessStatus()
    {
        return $this->processStatus;
    }

    /**
     * Set processMemo
     *
     * @param string $processMemo
     *
     * @return Inquiry
     */
    public function setProcessMemo($processMemo)
    {
        $this->processMemo = $processMemo;

        return $this;
    }

    /**
     * Get processMemo
     *
     * @return string
     */
    public function getProcessMemo()
    {
        return $this->processMemo;
    }
}
