<?php

namespace AppBundle\Controller; #本ファイルのパスを名前として定義

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminMenuController extends Controller #Symfony/.../Controllerのメンバや処理内容を継承
{
    /**
     * @Route("/admin/")
     */
    public function indexAction()
    {
        return $this->render('Admin/Common/index.html.twig'); #共通部品のサイドメニューバーをrender()で表示
    }
}
