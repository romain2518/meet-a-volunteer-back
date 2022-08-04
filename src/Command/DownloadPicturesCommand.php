<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DownloadPicturesCommand extends Command
{
    protected static $defaultName = 'app:downloadPictures';
    protected static $defaultDescription = 'Download pictures from copyright free websites.';

    public function __construct() {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        //? User profile pictures
        $ppProgressBar = new ProgressBar($output, 30);

        for ($i=1; $i < 31; $i++) { 
            $content = file_get_contents("https://i.pravatar.cc/200?img=" . $i);
    
            //Store in the filesystem.
            $fp = fopen("public/images/pp/" . $i . ".jfif", "w");
            fwrite($fp, $content);
            fclose($fp);

            $ppProgressBar->advance();
        }

        $io->info('Profile pictures => Ok!');

        //? Experience pictures
        $experienceProgressBar = new ProgressBar($output, 50);

        for ($i=1; $i < 50; $i++) { 
            $content = file_get_contents("https://picsum.photos/500/350");
    
            //Store in the filesystem.
            $fp = fopen("public/images/experiencePicture/" . $i . ".jpg", "w");
            fwrite($fp, $content);
            fclose($fp);

            $experienceProgressBar->advance();
        }

        $io->info('Experience pictures => Ok!');

        $io->success("Success: 30 profile pictures and 50 experiences pictures were downloaded !");
        return Command::SUCCESS;
    }
}
