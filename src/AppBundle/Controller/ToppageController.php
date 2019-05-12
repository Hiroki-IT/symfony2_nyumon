<?php

namespace AppBundle\Controller; #本ファイルのパスを名前として定義

#use文で他のファイルのclassにアクセスする
use Symfony\Bundle\FrameworkBundle\Controller\Controller; #Controllerクラスをuse文で指定
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ToppageController extends Controller #Symfony/.../Controllerのメンバや処理内容を継承
{  
    #指定のURLがリクエストされる
    #⇒カーネルが、URLとマッピングされるコントローラを探し、このコントローラにたどり着く（ルーティング）
    #⇒コントローラ名とアクション名がカーネルに返る
    #⇒カーネルがこのコントローラ／アクションを呼び出す

    /**
    * @Route("/") 
    */
    public function indexAction()
    {
        $information="5月と6月の公演情報を追加しました。"; #新着情報を変数に格納
        
        return $this->render('Toppage/index.html.twig',#同じclass内のメンバ変数を使うために疑似変数を使用。テンプレートを指定
            ['information'=>$information] #テンプレートへ変数を渡す
        );
    }
}
