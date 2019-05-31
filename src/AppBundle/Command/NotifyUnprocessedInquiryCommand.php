<?php

#未処理お問い合わせ通知コマンドのクラスを作成

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
            #コマンドの名前を設定
            ->setName('cs:inquiry:notify-unprocessed')

            #コマンドの説明を設定
            ->setDescription('未処理お問い合わせ一覧を通知');
    }

    #コマンドの実際の処理を設定
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        #エンティティマネージャを取得
        $em = $container->get('doctrine')->getManager();

        #引数でエンティティを指定
        $inquiryRepository = $em->getRepository('AppBundle:Inquiry');

        #エンティティから未処理お問い合わせ一覧をfindUnprocessed()で取得
        $inquiryList = $inquiryRepository->findUnprocessed();

        #未処理お問い合わせ一覧が一つ以上あれば…
        if (count($inquiryList) > 0) {
            $templating = $container->get('templating');

            #新しいインスタンスを作成。インスタンスに引数を指定した場合、コンストラクタに渡す。
            $message = \Swift_Message::newInstance()

            #件名を設定
            ->setSubject('[CS] 未処理お問い合わせ通知')

            #送信元を設定
            ->setFrom('webmaster@example.com')

            #部分的にレンダリング
            ->setBody($templating->render('mail/admin_unprocessedInquiryList.txt.twig', ['inquiryList' => $inquiryList]));

            #メール送信メソッドの引数として
            $container->get('mailer')->send($message);

            $output->writeln(count($inquiryList) . "件の未処理お問い合わせがあります");

            #メール送信の確認処理を実行
            if ($this->confirmSend($input, $output)){
                $this->sendMail($inquiryList, $output);
            }
        } else {
            $output->writeln("未処理なし");
        }
    }

    private function confirmSend($input, $output)
    {
        #Questionヘルパーを取得
        $qHelper = $this->getHelper('question');

        #質問文を設定
        $question = new Question('通知メールを送信しますか？[y/n]:', null);

        #回答のバリデーションを準備
        $question->setValidator(function ($answer) {
            $answer = strtolower(substr($answer, 0, 1));

            #'y'あるいは'n'以外の文字の場合、例外をスロー
            if(!in_array($answer, ['y', 'n'])) {
                throw new \RuntimeException(
                    'yまたはnを入力してください'
                );
            }
            return $answer;
        });

        #許容する試行回数の設定
        $question->setMaxAttempts(3);

        #プロンプトを表示して回答を取得
        return $qHelper->ask($input, $output, $question) == 'y';
    }

    private function sendMail($inquiryList, $output)
    {
        $container = $this->getContainer();

        #メール送信のコード
        $templating = $container->get('templating');
    }
}