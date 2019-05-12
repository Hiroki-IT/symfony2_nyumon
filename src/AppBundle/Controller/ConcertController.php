<?php

namespace AppBundle\Controller; #本ファイルのパスを名前として定義

#use文で他のファイルのclassにアクセスする
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConcertController extends Controller #Symfony/.../Controllerのメンバや処理内容を継承
{
    #指定のURLがリクエストされる
    #⇒カーネルが、URLとマッピングされるコントローラを探し、このコントローラにたどり着く（ルーティング）
    #⇒コントローラ名とアクション名がカーネルに返る
    #⇒カーネルがこのコントローラ／アクションを呼び出す
    
    /**
     * @Route("/concert/")
     */
    public function indexAction()
    {
        $concertList = [ #連想配列を定義
            [
                'date' => '2015年5月3日',
                'time' => '14:00',
                'place' => '東京文化会館(満席)',
                'available' => false, #予測可能フラグを立てて、表示を分岐させる
            ],
            [
                'date' => '2015年7月12日',
                'time' => '14:00',
                'place' => '鎌倉芸術館',
                'available' => true,
            ],
            [
                'date' => '2015年9月20日',
                'time' => '15:00',
                'place' => '横浜みなとみらいホール',
                'available' => true,
            ], 
            [
                'date' => '2015年11月8日',
                'time' => '15:00',
                'place' => 'よこすか芸術劇場(満席)',
                'available' => false,
            ], 
            [
                'date' => '2016年1月10日',
                'time' => '14:00',
                'place' => '渋谷公会堂',
                'available' => true,
            ],
        ];

        return $this->render('Concert/index.html.twig', #同じclass内のメンバ変数を使うために疑似変数を使用。index.html.twigをレンダリング
            ['concertList' => $concertList] #公演情報配列をテンプレートへ渡す
        );
    }
}
