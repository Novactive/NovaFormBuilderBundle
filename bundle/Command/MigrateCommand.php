<?php

/**
 * NovaFormBuilder Bundle.
 *
 * @package   Novactive\Bundle\FormBuilderBundle
 *
 * @author    Maxim Strukov <m.strukov@novactive.com>
 * @copyright 2018 Novactive
 * @license   https://github.com/Novactive/NovaFormBuilderBundle/blob/master/LICENSE MIT Licence
 */

declare(strict_types=1);

namespace Novactive\Bundle\FormBuilderBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

/**
 * Class MigrateCommand.
 */
class MigrateCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'novaformbuilder:migrate';

    /**
     * @var Connection
     */
    private $connection;

    public const QUESTION_TYPES = ['EmailEntry', 'TextEntry', 'NumberEntry', 'Paragraph', 'MultipleChoice'];

    /**
     * MigrateCommand constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Import database from the old one.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Update the Database with Custom Novactive Form Builder Tables');

        $forms = [];

        $surveys = $this->connection->query(
            'SELECT max(id) as surveyId FROM ezsurvey GROUP BY contentobject_id ORDER BY surveyId'
        )->fetchAll(FetchMode::COLUMN);
        foreach ($surveys as $surveyId) {
            $fields = [];
            $stmt   = $this->connection->prepare(
                "SELECT * FROM ezsurveyquestion WHERE survey_id = ? AND `type` IN ('".
                implode("','", self::QUESTION_TYPES)."') ORDER BY tab_order"
            );
            $stmt->bindValue(1, $surveyId);
            $stmt->execute();
            $questions = $stmt->fetchAll();
            foreach ($questions as $question) {
                //dump($question);
                switch ($question['type']) {
                    case 'EmailEntry':
                        $type = 'Email';
                        break;
                    case 'TextEntry':
                        $type = 'TextLine';
                        break;
                    case 'NumberEntry':
                        $type = 'Number';
                        break;
                    case 'Paragraph':
                        $type = 'TextArea';
                        break;
                    case 'MultipleChoice':
                        $type = 'Choice';
                        break;
                    default:
                        $type = '';
                }
                $fields[] = [
                    'name'   => $question['text'], 'required' => $question['mandatory'],
                    'weight' => $question['tab_order'], 'options' => [], 'type' => $type
                ];
                break;
            }
            $forms[] = ['name' => 'Form_'.$surveyId, 'maxSubmissions' => 0, 'fields' => $fields, 'submissions' => []];
        }
        file_put_contents('dump.json', json_encode($forms));

        $io->success('Done.');
    }
}
