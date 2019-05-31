<?php

#本ファイルのパスを名前として定義
namespace AppBundle\Controller;

use AppBundle\Entity\Inquiry;
use League\Csv\Writer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @Route("/admin/inquiry")
 */
class AdminInquiryListController extends Controller #Symfony/.../Controllerのメンバや処理内容を継承
{
    /**
     * @Route("/search.{_format}", defaults={"_format":"html"}, requirements={"_format":"html|csv"})
     * #（１）{_format}で、拡張子をパラメータ化
     * #（２）フォーマットのデフォルト値をhtmlに設定
     * #（３）パラメータの条件として、htmlあるいはcsvを設定
     */
    #引数の型（Requestクラス）宣言を行い、$requestと$_formatを受け取る
    #indexAction()：indexを返り値とするアクション
    public function indexAction(Request $request, $_format)
    {
        #createSearchForm()の返り値を$formに格納
        $form = $this->createSearchForm();

        #送信されたHTTPリクエストをフォームオブジェクトに取り込む
        $form->handleRequest($request);
        $keyword = null;
        if($form->isValid()){

            #キーワード検索フォームにアクセスし、searchデータを連想配列として取り出し、$keywordに格納
            $keyword = $form->get('search')->getData();
        }

        #getManagerにアクセスして発動。Doctrineオブジェクトを取得し、エンティティマネージャを取得
        $em = $this->getDoctrine()->getManager();

        #引数でエンティティを指定
        $inquiryRepository = $em->getRepository('AppBundle:Inquiry');

        #findAllByKeyword()でキーワードに一致するお問い合わせ一覧を取得し、$inquiryListに格納
        $inquiryList = $inquiryRepository->findAllByKeyword($keyword);

        #$_formatがcsvならば発動
        if($_format == 'csv'){

            #$inquiryListから、csv形式のレスポンスを作成
            $response = new Response($this->createCsv($inquiryList));
            $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'inquiry_list.csv');
            $response->headers->set('Content-Disposition', $d);

            return $response;
        }

        #createView()で、$formのクラスをインスタンス化
        return $this->render('Admin/Inquiry/index.html.twig', ['form' => $form->createView(), 'inquiryList' => $inquiryList]);

    }

    #キーワード検索フォームを作成
    private function createSearchForm()
    {
        #createXXX()：指定のものを作成する関数
        return $this->createFormBuilder()

            #add()でフィールドを設定。第１引数：フィールドの識別名、第２引数：フィールドのタイプ、第３引数：フィールドのオプションを連想配列で指定
            ->add('search', SearchType::class)
            ->add('submit', ButtonType::class, ['label' => '検索'])
            ->getForm(); 
    }

    #csvオブジェクトを作成
    private function createCsv($inquiryList)
    {
        /**
         * @var Writer $writer #引数の型（Writerクラス）宣言を行い、$writerを受け取る
         */

        #スコープ定義演算子（クラスのプロパティやメソッドにアクセスするためには一度newインスタンスすることが必要だが、それを省略できる）
        $writer = Writer::createFromString(",");
        $writer->setNewline("\r\n");

        foreach($inquiryList as $inquiry){
            /**
             * @var Inquiry $inquiry #引数の型（Inquiryクラス）宣言を行い、$inquiryを受け取る
             */

            #getId()：$inquiry内の$idプロパティ（＝idエンティティ）を返す。
            $writer->insertOne([ $inquiry->getId(), $inquiry->getName(), $inquiry->getEmail()]);
        }

        #csvオブジェクトを文字列化し、文字列として取り出して返す
        return (string)$writer;
    }
}
