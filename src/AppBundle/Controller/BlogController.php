<?php

#本ファイルのパスを名前として定義
namespace AppBundle\Controller;

#use文で他のファイルのclassにアクセスする
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

#Symfony/.../Controllerのメンバや処理内容を継承
class BlogController extends Controller
{
    public function latestListAction()
    {
        #Doctrineオブジェクトを取得し、エンティティマネージャを取得
        $em = $this->getDoctrine()->getManager();

        #引数でエンティティを指定
        $blogArticleRepository = $em->getRepository('AppBundle:BlogArticle');

        #エンティティリポジトリから、findBy()でtargetDate列を日付降順で配列として取り出す
        $blogList = $blogArticleRepository->findBy([], ['targetDate' => 'DESC']);

        #同じclass内のメンバ変数を使うために疑似変数を使用。
        return $this->render('Blog/latestList.html.twig',
            ['blogList' => $blogList]
        );
    }
}
