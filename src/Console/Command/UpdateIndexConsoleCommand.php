<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ElasticBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmunkeez\ElasticBundle\Client\ElasticClientInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class UpdateIndexConsoleCommand extends Command
{
    private ElasticClientInterface $elasticClient;

    private array $elasticIndicesConfig;

    public function __construct(ElasticClientInterface $elasticClient, array $elasticIndicesConfig)
    {
        parent::__construct();

        $this->elasticClient = $elasticClient;
        $this->elasticIndicesConfig = $elasticIndicesConfig;
    }

    protected function configure(): void
    {
        $this->setDescription('Update an Elasticsearch index')
            ->addArgument('index_name', InputArgument::REQUIRED, 'The name of the index');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (false === isset($this->elasticIndicesConfig[$input->getArgument('index_name')])) {
            $output->writeln('This index `'.$input->getArgument('index_name').'` does not exist in configuration.');

            return Command::FAILURE;
        }

        $this->elasticClient->closeIndices([
            'index' => $input->getArgument('index_name'),
        ]);

        if (true === isset($this->elasticIndicesConfig[$input->getArgument('index_name')]['settings'])) {
            $this->elasticClient->putIndicesSettings([
                'index' => $input->getArgument('index_name'),
                'body' => [
                    'settings' => $this->elasticIndicesConfig[$input->getArgument('index_name')]['settings'],
                ],
            ]);
        }

        if (true === isset($this->elasticIndicesConfig[$input->getArgument('index_name')]['mappings']['properties'])) {
            $this->elasticClient->putIndicesMapping([
                'index' => $input->getArgument('index_name'),
                'body' => [
                    'properties' => $this->elasticIndicesConfig[$input->getArgument('index_name')]['mappings']['properties'],
                ],
            ]);
        }

        $this->elasticClient->openIndices([
            'index' => $input->getArgument('index_name'),
        ]);

        $output->writeln('The index `'.$input->getArgument('index_name').'` has been updated.');

        return Command::SUCCESS;
    }
}
