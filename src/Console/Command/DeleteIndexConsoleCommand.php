<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ElasticBundle\Console\Command;

use Elastic\Elasticsearch\Exception\ClientResponseException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Webmunkeez\ElasticBundle\Client\ElasticClientInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class DeleteIndexConsoleCommand extends Command
{
    private ElasticClientInterface $elasticClient;

    private array $elasticIndicesConfig;

    private string $env;

    public function __construct(ElasticClientInterface $elasticClient, array $elasticIndicesConfig, string $env)
    {
        parent::__construct();

        $this->elasticClient = $elasticClient;
        $this->elasticIndicesConfig = $elasticIndicesConfig;
        $this->env = $env;
    }

    protected function configure(): void
    {
        $this->setDescription('Delete an Elasticsearch index')
            ->addArgument('index_name', InputArgument::REQUIRED, 'The name of the index')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force deletion');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (false === isset($this->elasticIndicesConfig[$input->getArgument('index_name')])) {
            $output->writeln('This index `'.$input->getArgument('index_name').'` does not exist in configuration.');

            return Command::FAILURE;
        }

        try {
            $this->elasticClient->deleteIndices([
                'index' => $input->getArgument('index_name').'_'.$this->env,
            ]);

            $output->writeln('The index `'.$input->getArgument('index_name').'_'.$this->env.'` has been deleted.');
        } catch (ClientResponseException $e) {
            if (
                Response::HTTP_NOT_FOUND === $e->getCode()
                && false === $input->getOption('force')
            ) {
                throw $e;
            }

            $output->writeln('The index `'.$input->getArgument('index_name').'_'.$this->env.'` has not been deleted cause it did not exist.');
        }

        return Command::SUCCESS;
    }
}
