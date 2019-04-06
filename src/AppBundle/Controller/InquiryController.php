<?php

namespace AppBundle\Controller; //同じ名前の関数は使えないため、namespaceで名前の衝突を防ぐ

use Symfony\Bundle\FrameworkBundle\Controller\Controller; //use文で他のファイルのclassにアクセスする
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; //Methodファイルを使うためのuse文を追加
use Symfony\Component\Form\Extension\Core\Type\TextType; //TextTypeを使うためのuse文を追加
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/inquiry") //コントローラ全体で基準とするURL
 */
//指定のURL(/inquiry)がリクエストされる
//⇒カーネルが、URL(/blog)とマッピングされているコントローラを探し、このコントローラにたどり着く（ルーティング）
//⇒コントローラ名とアクション名がカーネルに返る
//⇒カーネルがこのコントローラ／アクションを呼び出す
class InquiryController extends Controller
{
    /**
    * @Route("/")
    * @Method("get") //HTTPメソッドを限定
    */

    //指定のURL(/)がリクエストされる
    //⇒カーネルが、URL(/blog)とマッピングされているコントローラを探し、このコントローラにたどり着く（ルーティング）
    //⇒コントローラ名とアクション名がカーネルに返る
    //⇒カーネルがこのコントローラ／アクションを呼び出す

    public function indexAction()
    {
        $form = $this->createFormBuilder() //サービスコンテナからcreateFormBuilderオブジェクトを取得
            ->add('name', TextType::class) //add()メソッドを呼び出し、//第１引数：フィールドの識別名、第２引数：フィールドのタイプ、第３引数：フィールドのオプションを連想配列で指定
            ->add('email', TextType::class) 
            ->add('tel', TextType::class, [
                'required' => false,
            ])
            ->add('type', ChoiceType::class,[
                'choices' => [
                    '公演について' => '公演について', //キーのテキスト名がウェブページに表記される。
                    'その他' => 'その他',
                ],
                'expanded' => true,
            ])
            ->add('content', TextareaType::class)
            ->add('submit', SubmitType::class,[ //送信ボタンをフォームの要素として追加
                'label' => '送信',
            ])
            ->getForm(); //フォームをオブジェクトにして返し、$formへ格納
    
        return $this->render('Inquiry/index.html.twig', //index.html.twigをレンダリング
            ['form' => $form->createView()] //シングルアロー（$formオブジェクトのcreateView()メソッドにアクセス）。ダブルアロー（配列のキーとバリューの関係を作る）
        );
    }
}
