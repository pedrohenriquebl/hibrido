<?php

namespace Barcellos\Color\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ButtonColor
 */
class ButtonColor extends Command
{
    const buttonColor = 'buttonColor';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('change:button:color');
        $this->setDescription('Change the color of all buttons.');
        $this->addOption(
            self::buttonColor,
            null,
            InputOption::VALUE_REQUIRED,
            'buttonColor'
        );

        parent::configure();
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($name = $input->getOption(self::buttonColor)) {
            $output->writeln('<info>Provided name is `' . $name . '`</info>');
        }

        $output->writeln('<info>Success Message.</info>');
        $output->writeln('<error>An error encountered.</error>');
        $output->writeln('<comment>Some Comment.</comment>');
    }
}
