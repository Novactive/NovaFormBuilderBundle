<?php
/**
 * NovaFormBuilder package.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Novactive <s.morel@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class InstallCommand.
 */
class InstallCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'novaformbuilder:install';

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Install what necessary in the DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Update the Database with Custom Novactive Form Builder Tables');
        $command    = $this->getApplication()->find('doctrine:schema:update');
        $arguments  = [
            'command'    => 'doctrine:schema:update',
            '--dump-sql' => true,
            '--force'    => true,
        ];
        $arrayInput = new ArrayInput($arguments);
        $command->run($arrayInput, $output);

        $io->success('Done.');
    }
}
