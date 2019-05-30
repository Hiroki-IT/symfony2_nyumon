<?php

//未処理お問い合わせ通知コマンドのクラスを作成

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\QuestionHelper;

class NotifyUnprocessedInquiryCommand extends ContainerAwareCommand
{
    #コマンドの名前や引数を設定
    protected function configure()
    {
        $this
            ->setName('cs:inquiry:notify-unprocessed')#コマンドの名前を設定
            ->setDescription('未処理お問い合わせ一覧を通知'); #コマンドの説明を設定
    }

    #コマンドの実際の処理を設定
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $em = $container->get('doctrine')->getManager(); #エンティティマネージャを取得
        $inquiryRepository = $em->getRepository('AppBundle:Inquiry'); #引数でエンティティを指定

        $inquiryList = $inquiryRepository->findUnprocessed(); #エンティティから未処理お問い合わせ一覧をfindUnprocessed()で取得

        if (count($inquiryList) > 0) { #未処理お問い合わせ一覧が一つ以上あれば…
            $templating = $container->get('templating');

            $message = \Swift_Message::newInstance()#新しいインスタンスを作成。インスタンスに引数を指定した場合、コンストラクタに渡す。
            ->setSubject('[CS] 未処理お問い合わせ通知')#件名を設定
            ->setFrom('webmaster@example.com')#送信元を設定
            ->setBody($templating->render('mail/admin_unprocessedInquiryList.txt.twig', ['inquiryList' => $inquiryList])); #部分的にレンダリング

            $container->get('mailer')->send($message); #メール送信メソッドの引数として

            $output->writeln(count($inquiryList) . "件の未処理お問い合わせがあります");

            if ($this->confirmSend($input, $output)){ #メール送信の確認処理を実行
                $this->sendMail($inquiryList, $output);
            }
        } else {
            $output->writeln("未処理なし");
        }
    }

    private function confirmSend($input, $output)
    {
        $qHelper = $this->getHelper('question'); #Questionヘルパーを取得

        $question = new Question('通知メールを送信しますか？[y/n]:', null); #質問文を設定

        $question->setValidator(function ($answer) { #回答のバリデーションを準備
            $answer = strtolower(substr($answer, 0, 1));
            if(!in_array($answer, ['y', 'n'])) { #'y'あるいは'n'以外の文字の場合、例外をスロー
                throw new \RuntimeException(
                    'yまたはnを入力してください'
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(3); #許容する試行回数の設定

        return $qHelper->ask($input, $output, $question) == 'y'; #プロンプトを表示して回答を取得
    }

    private function sendMail($inquiryList, $output)
    {
        $container = $this->getContainer();
        $templating = $container->get('templating'); #メール送信のコード
    }
}