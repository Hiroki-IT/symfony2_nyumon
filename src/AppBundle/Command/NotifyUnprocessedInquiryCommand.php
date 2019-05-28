<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyUnprocessedInquiryCommand extends ContainerAwareCommand
{
    protected function configure(){
        $this
            ->setName('cs:inquiry:notify-unprocessed') //エンティティに引数をname変数として設定
            ->setDescription('未処理お問い合わせ一覧を通知');
    }

    protected function execute(InputInterface $input, OutputInterface $output){ #クラス修飾子のインターフェイスを引数の型として宣言
        $container = $this->getContainer();

        $em = $container->get('doctrine')->getManager(); #エンティティマネージャを取得
        $inquiryRepository = $em->getRepository('AppBundle:Inquiry'); #引数でエンティティを指定

        $inquiryList = $inquiryRepository->findUnprocessed(); #未処理お問い合わせ一覧をfindUnprocessed()で取得

        if(count($inquiryList)>0){ #未処理お問い合わせ一覧が一つ以上あれば…
            $templating = $container->get('templating'); 

            $message = \Swift_Message::newInstance() #新しいインスタンスを作成。インスタンスに引数を指定した場合、コンストラクタに渡す。
            ->setSubject('[CS] 未処理お問い合わせ通知') #件名を設定
            ->setFrom('webmaster@example.com') #送信元を設定
            ->setBody($templating->render('mail/admin_unprocessedInquiryList.txt.twig', ['inquiryList' => $inquiryList])); #部分的にレンダリング

            $container->get('mailer')->send($message); #メール送信メソッドの引数として

            $output->writeln(count($inquiryList)."件の未処理を通知");
        }
        else{
            $output->writerln("未処理なし");
        }
    }
}