<?php

namespace AppBundle\Controller; #同じ名前の関数は使えないため、namespaceで名前の衝突を防ぐ

#use文で他のファイルのclassにアクセスする
use AppBundle\Entity\Inquiry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; #Methodファイルを使うためのuse文を追加
use Symfony\Bundle\FrameworkBundle\Controller\Controller; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType; #TextTypeを使うためのuse文を追加
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#指定のURLがリクエストされる
#⇒カーネルが、URLとマッピングされているコントローラを探し、このコントローラにたどり着く（ルーティング）
#⇒コントローラ名とアクション名がカーネルに返る
#⇒カーネルがこのコントローラ／アクションを呼び出す

/**
 * @Route("/inquiry") #コントローラ全体で基準とするURL
 */
class InquiryController extends Controller
{
    /**
     * @Route("/")
     * @Method("post") #HTTPメソッドをPOST送信に限定
     */
    public function indexPostAction(Request $request)
    {
        $form = $this->createInquiryForm(); #同じclass内のメンバ変数を使うために疑似変数を使用。フォーム定義を取得
        $form->handleRequest($request); #formオブジェクトから呼び出す。クライアントから送信された情報をフォームオブジェクトに取り込む
        if($form->isValid()) #もし入力値が正しかった場合、データベースへ情報を反映し、通知メールを送り、完了ページへリダイレクトする。
        { 
            /*『return $this->createFormBuilder(new Inquiry())』を追加したのでいらない。
            
            $data = $form->getData(); #フォームオブジェクトの入力データ全体を連想配列として取り出し、$dataに格納
            $inquiry = new Inquiry(); #Inquiryエンティティからインスタンスを作成
            $inquiry->setName($data['name']); #$inquiryのsetName()で$dataからname値を取り出す
            $inquiry->setEmail($data['email']);
            $inquiry->setTel($data['tel']);
            $inquiry->setType($data['type']);
            $inquiry->setContent($data['content']);
            
            */

            $inquiry = $form->getData(); #フォームオブジェクトの入力データ全体を連想配列として取り出し、$inquiryに格納

            $em = $this->getDoctrine()->getManager(); #Entityマネージャを取得
            $em->persist($inquiry); #InquiryエンティティのインスタンスをDoctrineの管理下へ
            $em->flush(); #変更をデータベースへ反映

            $message = \Swift_Message::newInstance() #メールメッセージオブジェクトの作成
                ->setSubject('Webサイトからのお問い合わせ') #メールの件名を設定
                ->setFrom('webmaster@example.com')
                ->setTo('admin@example.com')
                ->setBody(
                    $this->renderView( #テンプレートから本文を作成
                        'mail/inquiry.txt.twig',
                        ['data' => $inquiry] #$dataをキーに、$inquiryをバリューとする。
                    )
                );
            
            $this->get('mailer')->send($message); #同じclass内のメンバ変数を使うために疑似変数を使用。

            return $this->redirect(
                $this->generateUrl('app_inquiry_complete')); #何らかの処理を行った後、指定の『ルート名』にリダイレクト（php bin/console debug:routerで確認）
        } 
        
        return $this->render('Inquiry/index.html.twig', #同じclass内のメンバ変数を使うために疑似変数を使用。#入力エラーの場合は同じフォームを出力
            ['form' => $form->createView()] #$formをキーに、createViewの返り値をバリューとする。
        );
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
            ->add('name', TextType::class) #add()メソッドを呼び出し、#第１引数：フィールドの識別名、第２引数：フィールドのタイプ、第３引数：フィールドのオプションを連想配列で指定
            ->add('email', EmailType::class) 
            ->add('tel', TelType::class, [
                'required' => false,
            ])
            ->add('type', ChoiceType::class,[
                'choices' => [
                    '公演について' => '公演について', #キーのテキスト名がウェブページに表記される。
                    'その他' => 'その他',
                ],
                'expanded' => true,
            ])
            ->add('content', TextareaType::class)
            ->add('submit', SubmitType::class,[ #送信ボタンをフォームの要素として追加
                'label' => '送信',
            ])
            ->getForm(); #最後に、formオブジェクトにして返す
    }

    /**
     * @Route("/")
     * @Method("get") #HTTPメソッドをGET送信に限定
     */
    # このfunctionは、参考書通りだと一番上に配置するのだが、そうすると何故かリダイレクトが実行されなくなってしまう。
    public function indexAction()
    {
        return $this->render('Inquiry/index.html.twig',  #同じclass内のメンバ変数を使うために疑似変数を使用。
            ['form' => $this->createInquiryForm()->createView()] 
        );#シングルアロー（$formオブジェクトのcreateView()メソッドにアクセス）。ダブルアロー（配列のキーとバリューの関係を作る）
    }
}
