<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller; //Controllerクラスをuse文で指定
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ToppageController extends Controller
{  
    //指定のURLがリクエストされる
    //⇒カーネルが、URLとマッピングされるコントローラを探し、このコントローラにたどり着く（ルーティング）
    //⇒コントローラ名とアクション名がカーネルに返る
    //⇒カーネルがこのコントローラ／アクションを呼び出す

    /**
    * @Route("/") 
    */
    public function indexAction()
    {

    }
}
