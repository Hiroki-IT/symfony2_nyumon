<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConcertController extends Controller
{
    //指定のURLがリクエストされる
    //⇒カーネルが、URLとマッピングされるコントローラを探し、このコントローラにたどり着く（ルーティング）
    //⇒コントローラ名とアクション名がカーネルに返る
    //⇒カーネルがこのコントローラ／アクションを呼び出す
    
    /**
     * @Route("/concert/")
     */
    public function indexAction()
    {
        return $this->render("Concert/index.html.twig");
    }
}
