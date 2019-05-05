<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/admin/inquiry")
 */
class AdminInquiryListController extends Controller
{
    /**
     * @Route("/search")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createSearchForm(); #検索キーワード入力フォームを処理
        $form->handleRequest($request);
        $keyword = null;
        if($form->isValid()){
            $keyword = $form->get('search')->getData();
        }

        $em = $this->getDoctrine()->getManager(); #Doctrineオブジェクトを取得し、エンティティマネージャを取得
        $inquiryRepository = $em->getRepository('AppBundle:Inquiry'); #エンティティクラスとセットで使うリポジトリクラスのインスタンスを取得

        $inquiryList = $inquiryRepository->findAllByKeyword($keyword); #エンティティリポジトリのfindAllByKeyword()でキーワードに一致するお問い合わせ一覧を取得

        return $this->render('Admin/Inquiry/index.html.twig', ['form' => $form->createView(), 'inquiryList' => $inquiryList]);
    }

    private function createSearchForm() #検索キーワード入力フォームを作成
    {
        return $this->createFormBuilder()
            ->add('search', SearchType::class)
            ->add('submit', SubmitType::class, ['label' => '検索'])
            ->getForm();
    }
}
