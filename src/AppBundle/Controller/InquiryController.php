<?php

namespace AppBundle\Controller; #本ファイルのパスを名前として定義

#use文で他のファイルのclassにアクセスする
use AppBundle\Entity\Inquiry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; #Methodファイルを使うためのuse文を追加
use Symfony\Bundle\FrameworkBundle\Controller\Controller; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; #TextTypeを使うためのuse文を追加
use Symfony\Component\Form\Extension\Core\Type\TextType;


#指定のURLがリクエストされる
#⇒カーネルが、URLとマッピングされているコントローラを探し、このコントローラにたどり着く（ルーティング）
#⇒コントローラ名とアクション名がカーネルに返る
#⇒カーネルがこのコントローラ／アクションを呼び出す

/**
 * @Route("/inquiry") #コントローラ全体で基準とするURL
 */
class InquiryController extends Controller #Symfony/.../Controllerのメンバや処理内容を継承
{
    /**
     * @Route("/")
     * @Method("post") #HTTPリクエストのメソッドをPOST送信に限定
     */
    public function indexPostAction(Request $request) #引数の型（Requestクラス）宣言を行い、$requestを受け取る
    {
        $form = $this->createInquiryForm(); #createInquiryForm()の返り値を$formに格納
        $form->handleRequest($request); #formオブジェクトから呼び出す。クライアントから送信された情報をフォームオブジェクトに取り込む
        if($form->isValid()){ #もし入力値が正しかった場合、データベースへ情報を反映し、通知メールを送り、完了ページへリダイレクトする。
            
            $inquiry = $form->getData(); #フォームオブジェクトの入力データ全体を連想配列として取り出し、$inquiryに格納

            $em = $this->getDoctrine()->getManager(); #Doctrineオブジェクトを取得し、エンティティマネージャを取得
            $em->persist($inquiry); #InquiryエンティティのインスタンスをDoctrineの管理下へ
            $em->flush(); #変更をデータベースへ反映

            $message = \Swift_Message::newInstance() #メールメッセージオブジェクトの作成
                ->setSubject('Webサイトからのお問い合わせ') #メールの件名を設定
                ->setFrom('webmaster@example.com')
                ->setTo('admin@example.com')
                ->setBody($this->renderView('mail/inquiry.txt.twig', ['data' => $inquiry]));
                #テンプレートから本文を作成
                #$dataをキーに、$inquiryをバリューとする。
            
            $this->get('mailer')->send($message); #同じclass内のメンバ変数を使うために疑似変数を使用。

            return $this->redirect($this->generateUrl('app_inquiry_complete')); #何らかの処理を行った後、指定の『ルート名』にリダイレクト（php bin/console debug:routerで確認）
        } 
        
        return $this->render('Inquiry/index.html.twig', ['form' => $form->createView()]);
        #同じclass内のメンバ変数を使うために疑似変数を使用。#入力エラーの場合は同じフォームを出力
        #$formをキーに、createViewの返り値をバリューとする。
    }

    /**
     * @Route("/complete")
     */
    public function completeAction()
    {
        return $this->render('Inquiry/complete.html.twig');
    }



    private function createInquiryForm() #フォームを定義する関数を作成
    {
        return $this->createFormBuilder(new Inquiry()) #同じclass内のメンバ変数を使うために疑似変数を使用。#フォームのデータをEntityのインスタンスに格納。
            ->add('name', TextType::class) #add()でフィールドを追加。第１引数：フィールドの識別名、第２引数：フィールドのタイプ、第３引数：フィールドのオプションを連想配列で指定
            ->add('email', EmailType::class) 
            ->add('tel', TelType::class, ['required' => false])
            ->add('type', ChoiceType::class,['choices' => ['公演について' => '公演について', 'その他' => 'その他'], 'expanded' => true]) #キーのテキスト名がウェブページに表記される。
            ->add('content', TextareaType::class)
            ->add('submit', SubmitType::class,['label' => '送信']) #送信ボタンをフォームの要素として追加
            ->getForm(); #最後に、formオブジェクトにして返す
    }

    /**
     * @Route("/")
     * @Method("get") #HTTPリクエストのメソッドをGET送信に限定
     */
    # このfunctionは、参考書通りだと一番上に配置するのだが、そうすると何故かリダイレクトが実行されなくなってしまう。
    public function indexAction()
    {
        return $this->render('Inquiry/index.html.twig',  ['form' => $this->createInquiryForm()->createView()]);
        #同じclass内のメンバ変数を使うために疑似変数を使用。
        #シングルアロー（$formオブジェクトのcreateView()メソッドにアクセス）。ダブルアロー（配列のキーとバリューの関係を作る）
    }
}
