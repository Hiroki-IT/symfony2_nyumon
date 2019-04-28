<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin/inquiry")
 */

class AdminInquiryListController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager(); #Doctrineオブジェクトを取得し、エンティティマネージャを取得
        $inquiryRepository = $em->getRepository('AppBundle:Inquiry'); #エンティティクラスとセットで使うリポジトリクラスのインスタンスを取得
        $inquiryList = $inquiryRepository->findBy([], ['id' => 'DESC']); #エンティティリポジトリから、findBy()でid列を降順で配列として取り出す

        return $this->render('Admin/Inquiry/index.html.twig',
            ['inquiryList' => $inquiryList]
        );
    } 
}
