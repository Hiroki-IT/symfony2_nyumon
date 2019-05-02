<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Inquiry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; #TextTypeを使うためのuse文を追加
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/inquiry")
 */
class AdminInquiryEditController extends Controller
{
    /**
     * @Route("/{id}/edit")
     * @ParamConverter("inquiry", class="AppBundle:Inquiry") #URLで指定されたidの値から自動的にエンティティを取得
     * @Method("post") #HTTPリクエストのメソッドをPOST送信に限定
     */
    public function inputPostAction(Request $request, Inquiry $inquiry) #RequestファイルとInquiryファイルにおける$requestと$inquiryへアクセス
    {
        $form = $this->createInquiryForm($inquiry); #同じclass内のメンバ変数を使うために疑似変数を使用。フォーム定義を取得
        $form->handleRequest($request); #formオブジェクトから呼び出す。クライアントから送信された情報をフォームオブジェクトに取り込む
        if($form->isValid()){ #もし入力値が正しかった場合、データベースへ情報を反映し、通知メールを送り、完了ページへリダイレクトする。

            $em = $this->getDoctrine()->getManager(); #Doctrineオブジェクトを取得し、エンティティマネージャを取得
            $em->flush(); #変更をデータベースへ反映

            return $this->redirect($this->generateUrl('app_admininquirylist_index'));
        }
        return $this->render('Admin/Inquiry/edit.html.twig', ['form' => $form->createView(), 'inquiry' => $inquiry]);
        #同じclass内のメンバ変数を使うために疑似変数を使用。#入力エラーの場合は同じフォームを出力
        #$formをキーに、createViewの返り値をバリューとする。
    }
    
    private function createInquiryForm($inquiry)
    {
        return $this->createFormBuilder($inquiry, ["validation_groups" => ["admin"]])
        ->add('processStatus', ChoiceType::class, ['choices' => ['未対応' => '未対応', '対応中' => '対応中', '対応済' => '対応済'], 'empty_data' => 0, 'expanded' => true])
        ->add('processMemo', TextareaType::class)
        ->add('submit', SubmitType::class, ['label' => '保存'])
        ->getForm(); #最後に、formオブジェクトにして返す
    }

    /**
     * @Route("/{id}/edit")
     * @ParamConverter("inquiry", class="AppBundle:Inquiry") #URLで指定されたidの値から自動的にエンティティを取得
     * @Method("get") #HTTPリクエストのメソッドをGET送信に限定
     */
    public function inputAction(Inquiry $inquiry)
    {
        $form = $this->createInquiryForm($inquiry);

        return $this->render('Admin/Inquiry/edit.html.twig', ['form' => $form->createView(), 'inquiry' => $inquiry]);
    }

}
