<?php

#本ファイルのパスを名前として定義
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

#Symfony/.../Controllerのメンバや処理内容を継承
class AdminMenuController extends Controller
{
    /**
     * @Route("/admin/")
     */
    public function indexAction()
    {

        #共通部品のサイドメニューバーをrender()で表示
        return $this->render('Admin/Common/index.html.twig');
    }
}
