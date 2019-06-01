<?php


namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

class ConcertController extends AbstractFOSRestController
{
    /**
     * #アノテーションのパラメータに拡張子を用いる
     * @Rest\Get("/api/concerts.{_format}")
     */
    public function getConcertsAction()
    {
        $em = $this->get('doctrine')->getManager();

        #対象とするEntityを引数で指定
        $repository = $em->getRepository('AppBundle:Concert');

        #findAllでデータを取得
        $concertList = $repository->findAll();

        #取得したデータからViewオブジェクトを作成
        $view = new View($concertList);

        #Viewオブジェクトからレスポンスオブジェクトを作成
        return $this->handleView($view);
    }
}