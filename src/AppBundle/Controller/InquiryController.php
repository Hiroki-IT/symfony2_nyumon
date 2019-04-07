<?php

namespace AppBundle\Controller; //同じ名前の関数は使えないため、namespaceで名前の衝突を防ぐ

//use文で他のファイルのclassにアクセスする
use Symfony\Bundle\FrameworkBundle\Controller\Controller; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; //Methodファイルを使うためのuse文を追加
use Symfony\Component\Form\Extension\Core\Type\TextType; //TextTypeを使うためのuse文を追加
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

//指定のURLがリクエストされる
//⇒カーネルが、URLとマッピングされているコントローラを探し、このコントローラにたどり着く（ルーティング）
//⇒コントローラ名とアクション名がカーネルに返る
//⇒カーネルがこのコントローラ／アクションを呼び出す

/**
 * @Route("/inquiry") //コントローラ全体で基準とするURL
 */
class InquiryController extends Controller
{
    private function createInquiryForm() //フォームを定義する関数を作成
    {
        return $this->createFormBuilder() //同じclass内のメンバ変数を使うために疑似変数を使用。
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
            ->getForm(); //最後に、formオブジェクトにして返す
    }

    /**
    * @Route("/")
    * @Method("get") //HTTPメソッドをGET送信に限定
    */
    public function indexAction()
    {
        return $this->render('Inquiry/index.html.twig',  //同じclass内のメンバ変数を使うために疑似変数を使用。
            ['form' => $this->createInquiryForm()->createView()] 
        );//シングルアロー（$formオブジェクトのcreateView()メソッドにアクセス）。ダブルアロー（配列のキーとバリューの関係を作る）
    }

    /**
     * @Route("/complete")
     */
    public function completeAction()
    {
        return $this->render('Inquiry/complete.html.twig');
    }

    /**
     * @Route("/")
     * @Method("post") //HTTPメソッドをPOST送信に限定
     */
    public function indexPostAction(Request $request)
    {
       $form = $this->createInquiryForm(); //同じclass内のメンバ変数を使うために疑似変数を使用。フォーム定義を取得
       $form->handleRequest($request); //formオブジェクトから呼び出す。クライアントから送信された情報をフォームオブジェクトに取り込む
       if($form->isValid()) //フォーム入力値のバリデーションを行う
       {
           return $this->redirect( //同じclass内のメンバ変数を使うために疑似変数を使用。
               $this->generateUrl('app_inquiry_complete')); //何らかの処理を行った後、完了ページへリダイレクト
         
           return $this->render('Inquiry/index.html.twig', //同じclass内のメンバ変数を使うために疑似変数を使用。
               ['form' => $form->createView()] //入力エラーの場合は同じフォームを出力
           );
        }
    }
}
