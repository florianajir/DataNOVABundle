<?php
namespace Fmaj\LaposteDatanovaBundle\Command;

use Fmaj\LaposteDatanovaBundle\Service\Downloader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Symfony 2.3+ DownloadDatasetCommand
 *
 * @author Florian Ajir <florianajir@gmail.com>
 */
class DownloadDatasetCommand extends ContainerAwareCommand
{
    /** @var Downloader $downloader */
    private $downloader;

    /**
     * Command configuration
     */
    protected function configure()
    {
        $this
            ->setName('datanova:download:dataset')
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

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->downloader = $this->getContainer()->get('data_nova.service.downloader');
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
        if ($download) {
            $output->writeln(sprintf(
                'Dataset %s downloaded to "%s" : %d bytes',
                $dataset,
                $filepath,
                filesize($filepath)
            ));
        } else {
            if (false !== $filepath) {
                if (false === $input->getOption('force-replace')) {
                    $output->writeln('Existing dataset. To overwrite it, try with --force-replace option');
                } else {
                    $output->writeln('Error during update of existing dataset.');
                }
            } else {
                $output->writeln('Error during dataset download.');
            }
        }
    }
}
