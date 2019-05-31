<?php

#本ファイルのパスを名前として定義
namespace AppBundle\Controller;

#use文で他のファイルのclassにアクセスする
use AppBundle\Entity\Inquiry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method; #Methodファイルを使うためのuse文
use Symfony\Bundle\FrameworkBundle\Controller\Controller; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; #TextTypeを使うためのuse文
use Symfony\Component\Form\Extension\Core\Type\TextType;


#指定のURLがリクエストされる
#⇒カーネルが、URLとマッピングされているコントローラを探し、このコントローラにたどり着く（ルーティング）
#⇒コントローラ名とアクション名がカーネルに返る
#⇒カーネルがこのコントローラ／アクションを呼び出す

/**
 * @Route("/inquiry") #コントローラ全体で基準とするURL
 */
#Symfony/.../Controllerのメンバや処理内容を継承
class InquiryController extends Controller
{
    /**
     * @Route("/", methods={"POST"}) #HTTPリクエストのメソッドをPOST送信に限定。参考書の書き方間違っている。
     */
    #引数の型（Requestクラス）宣言を行い、$requestを受け取る
    public function indexPostAction(Request $request)
    {
        #createInquiryForm()の返り値を$formに格納
        $form = $this->createInquiryForm();

        #formオブジェクトから呼び出す。クライアントから送信された情報をフォームオブジェクトに取り込む
        $form->handleRequest($request);

        #もし入力値が正しかった場合、データベースへ情報を反映し、通知メールを送り、完了ページへリダイレクトする。
        if($form->isValid()){

            #フォームオブジェクトの入力データ全体を連想配列として取り出し、$inquiryに格納
            $inquiry = $form->getData();

            #エンティティマネージャを取得
            $em = $this->getDoctrine()->getManager();

            #InquiryエンティティのインスタンスをDoctrineの管理下へ
            $em->persist($inquiry);

            #変更をデータベースへ反映
            $em->flush();

            #新しいインスタンスを作成。インスタンスに引数を指定した場合、コンストラクタに渡す。
            $message = \Swift_Message::newInstance()

                #件名を設定
                ->setSubject('Webサイトからのお問い合わせ')
                ->setFrom('webmaster@example.com')
                ->setTo('admin@example.com')

                #本文で、twigをレンダリング
                #テンプレートから本文を作成
                #$dataをキーに、$inquiryをバリューとする。
                ->setBody($this->renderView('mail/inquiry.txt.twig', ['data' => $inquiry]));

            #メール送信メソッドの引数として
            $this->get('mailer')->send($message);

            #何らかの処理を行った後、指定の『ルート名』にリダイレクト（php bin/console debug:routerで確認）
            return $this->redirect($this->generateUrl('app_inquiry_complete'));
        }

        #同じclass内のメンバ変数を使うために疑似変数を使用。#入力エラーの場合は同じフォームを出力
        #createView()で、$formのクラスをインスタンス化
        return $this->render('Inquiry/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/complete")
     */
    public function completeAction()
    {
        return $this->render('Inquiry/complete.html.twig');
    }


    #フォームを定義する関数を作成
    private function createInquiryForm()
    {
        #同じclass内のメンバ変数を使うために疑似変数を使用。#フォームのデータをEntityのインスタンスに格納。
        return $this->createFormBuilder(new Inquiry())

            #add()でフィールドを設定。第１引数：フィールドの識別名、第２引数：フィールドのタイプ、第３引数：フィールドのオプションを連想配列で指定
            ->add('name', TextType::class)
            ->add('email', EmailType::class) 
            ->add('tel', TelType::class, ['required' => false])
            ->add('type', ChoiceType::class,['choices' => ['公演について' => '公演について', 'その他' => 'その他'], 'expanded' => true]) #キーのテキスト名がウェブページに表記される。
            ->add('content', TextareaType::class)

            #送信ボタンをフォームの要素として設定
            ->add('submit', SubmitType::class,['label' => '送信'])
            ->getForm(); #最後に、formオブジェクトにして返す
    }

    /**
     * @Route("/", methods={"GET"}) #HTTPリクエストのメソッドをGET送信に限定。参考書の書き方間違っている。
     */
    # このfunctionは、参考書通りだと一番上に配置するのだが、そうすると何故かリダイレクトが実行されなくなってしまう。
    public function indexAction()
    {
        #同じclass内のメンバ変数を使うために疑似変数を使用。
        #シングルアロー（$formオブジェクトのcreateView()メソッドにアクセス）。ダブルアロー（配列のキーとバリューの関係を作る）
        return $this->render('Inquiry/index.html.twig',  ['form' => $this->createInquiryForm()->createView()]);

    }
}
