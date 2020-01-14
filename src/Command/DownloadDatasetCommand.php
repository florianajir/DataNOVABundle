<?php
namespace Fmaj\LaposteDatanovaBundle\Command;

use Fmaj\LaposteDatanovaBundle\Service\Downloader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Symfony DownloadDatasetCommand
 *
 * @author Florian Ajir <florianajir@gmail.com>
 */
class DownloadDatasetCommand extends Command
{
    protected static $defaultName = 'datanova:download:dataset';

    /** @var Downloader $downloader */
    private $downloader;

    public function __construct(Downloader $downloader)
    {
        $this->downloader = $downloader;
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Download dataset records to use it locally')
            ->addArgument(
                'dataset',
                InputArgument::REQUIRED,
                'Which dataset to download?'
            )
            ->addArgument(
                'format',
                InputArgument::OPTIONAL,
                'Data file format : CSV (default), JSON',
                'CSV'
            )
            ->addArgument(
                'q',
                InputArgument::OPTIONAL,
                'query filter, by default all results will be download'
            )
            ->addOption(
                'force-replace',
                'f',
                InputOption::VALUE_NONE,
                'If set, the command will replace local storage'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataset = $input->getArgument('dataset');
        $format = strtolower($input->getArgument('format'));
        $query = $input->getArgument('q');

        $download = $this->downloader->download(
            $dataset,
            $format,
            $input->getArgument('q'),
            $input->getOption('force-replace')
        );

        $filepath = $this->downloader->findDownload($dataset, $format, $query);

        if(! $filepath) {
            $output->writeln('Error during update of existing dataset.');

            return 1;
        }

        if (! $input->getOption('force-replace')) {
            $output->writeln('Existing dataset. To overwrite it, try with --force-replace option');

            return 1;
        }

        if (! $download) {
            $output->writeln('Error during dataset download.');

            return 1;
        }

        $output->writeln(sprintf(
            'Dataset %s downloaded to "%s" : %d bytes',
            $dataset,
            $filepath,
            filesize($filepath)
        ));

        return 0;
    }
}
