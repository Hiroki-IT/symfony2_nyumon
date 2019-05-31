<?php

//シリアライズの実行コマンドを作成

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    #コマンドの名前を設定
    protected function configure()
    {
        $this
            ->setName('cs:test')
            ->setDescription('test')
        ;
    }

    #コマンドの処理内容を記載
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        #公開情報をデータベースから取得
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $repository = $em->getRepository('AppBundle:Concert');
        $concertList = $repository->findAll();

        #シリアライザを取得
        $serializer = $container->get('jms_serializer');

        #serializerで取得したオブジェクト形式のデータをJSON形式に変換
        $json = $serializer->serialize($concertList, 'json');

        dump($json);
    }
}