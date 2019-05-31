<?php

namespace AppBundle\Controller; #本ファイルのパスを名前として定義

use AppBundle\Entity\Inquiry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/inquiry")
 */
#Symfony/.../Controllerのメンバや処理内容を継承
class AdminInquiryEditController extends Controller
{
    /**
     * @Route("/{id}/edit", methods={"POST"}) #HTTPリクエストのメソッドをPOST送信に限定。参考書の書き方間違っている。
     * @ParamConverter("inquiry", class="AppBundle:Inquiry") #引数でエンティティを指定
     */

    #引数の型（RequestクラスとInquiryクラス）宣言を行い、$requestと$inquiryを受け取る
    public function inputPostAction(Request $request, Inquiry $inquiry)
    {
        #createInquiryForm()の返り値を$formに格納
        $form = $this->createInquiryForm($inquiry);

        #formオブジェクトから呼び出す。クライアントから送信された情報をフォームオブジェクトに取り込む
        $form->handleRequest($request);

        #もし入力値が正しかった場合、データベースへ情報を反映し、通知メールを送り、完了ページへリダイレクトする。
        if($form->isValid()){

            #Doctrineオブジェクトを取得し、エンティティマネージャを取得
            $em = $this->getDoctrine()->getManager();

            #変更をデータベースへ反映
            $em->flush();

            return $this->redirect($this->generateUrl('app_admininquirylist_index'));
        }

        #同じclass内のメンバ変数を使うために疑似変数を使用。#入力エラーの場合は同じフォームを出力
        #createView()で、$formのクラスをインスタンス化
        return $this->render('Admin/Inquiry/edit.html.twig', ['form' => $form->createView(), 'inquiry' => $inquiry]);

    }
    
    private function createInquiryForm($inquiry)
    {
        return $this->createFormBuilder($inquiry, ["validation_groups" => ["admin"]])

        #add()でフィールドを設定。第１引数：フィールドの識別名、第２引数：フィールドのタイプ、第３引数：フィールドのオプションを連想配列で指定
        ->add('processStatus', ChoiceType::class, ['choices' => ['未対応' => '未対応', '対応中' => '対応中', '対応済' => '対応済'], 'empty_data' => 0, 'expanded' => true])
        ->add('processMemo', TextareaType::class)
        ->add('submit', SubmitType::class, ['label' => '保存'])
        ->getForm(); #最後に、formオブジェクトにして返す
    }

    /**
     * @Route("/{id}/edit", methods={"GET"}) #HTTPリクエストのメソッドをGET送信に限定。参考書の書き方間違っている。
     * @ParamConverter("inquiry", class="AppBundle:Inquiry") #引数でエンティティを指定
     */
    public function inputAction(Inquiry $inquiry)
    {
        #createInquiryForm()の返り値を$formに格納
        $form = $this->createInquiryForm($inquiry);

        return $this->render('Admin/Inquiry/edit.html.twig', ['form' => $form->createView(), 'inquiry' => $inquiry]);
    }

}
