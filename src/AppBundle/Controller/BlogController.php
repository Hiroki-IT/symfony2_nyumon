<?php

namespace AppBundle\Controller; #本ファイルのパスを名前として定義

#use文で他のファイルのclassにアクセスする
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BlogController extends Controller #Symfony/.../Controllerのメンバや処理内容を継承
{
    public function latestListAction()
    {
        $em = $this->getDoctrine()->getManager(); #Doctrineオブジェクトを取得し、エンティティマネージャを取得
        $blogArticleRepository = $em->getRepository('AppBundle:BlogArticle'); #引数でエンティティを指定
        $blogList = $blogArticleRepository->findBy([], ['targetDate' => 'DESC']); #エンティティリポジトリから、findBy()でtargetDate列を日付降順で配列として取り出す

        return $this->render('Blog/latestList.html.twig', #同じclass内のメンバ変数を使うために疑似変数を使用。
            ['blogList' => $blogList]
        );
    }
}
