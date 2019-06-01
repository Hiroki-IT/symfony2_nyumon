<?php


namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;

class ConcertController extends AbstractFOSRestController
{
    public function getConcertsAction()
    {
        $em = $this->get('doctrine')->getManager();

        #対象とするEntityを引数で指定
        $repository = $em->getRepository('AppBundle:Concert');

        #findAllでデータを取得
        $concertList = $repository->findAll();

        #エンティティの配列をそのままreturn
        return $concertList;
    }
}