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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Novactive\Bundle\eZFormBuilderBundle\Core\IOService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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

    /**
     * @var IOService
     */
    private $ioService;

    public const QUESTION_TYPES = ['EmailEntry', 'TextEntry', 'NumberEntry', 'Paragraph', 'MultipleChoice'];

    /**
     * MigrateCommand constructor.
     */
    public function __construct(Connection $connection, IOService $ioService)
    {
        parent::__construct();
        $this->connection = $connection;
        $this->ioService  = $ioService;
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
            'SELECT max(id) as surveyId, contentobject_id FROM ezsurvey GROUP BY contentobject_id ORDER BY surveyId'
        )->fetchAll();
        foreach ($surveys as $survey) {
            $fields = [];

            $sql = "SELECT * FROM ezsurveyquestion WHERE survey_id = ? 
                    AND `type` IN ('".implode("','", self::QUESTION_TYPES)."') ORDER BY tab_order";

            $questions = $this->runQuery($sql, [$survey['surveyId']]);
            foreach ($questions as $question) {
                if (empty($question['text'])) {
                    continue;
                }
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
                $options = [];
                if ('Choice' === $type) {
                    $xml     = simplexml_load_string($question['text2']);
                    $choices = [];
                    $counter = 0;
                    foreach ($xml as $option) {
                        ++$counter;
                        $choices[$counter] = ['value' => (string) $option->value, 'weight' => $counter];
                    }
                    switch ($question['num']) {
                        case 1:
                            $choiceType = 'radio';
                            break;
                        case 2:
                            $choiceType = 'radio';
                            break;
                        case 3:
                            $choiceType = 'checkboxes';
                            break;
                        case 4:
                            $choiceType = 'checkboxes';
                            break;
                        case 5:
                            $choiceType = 'dropdown';
                            break;
                        default:
                            $choiceType = 'dropdown';
                    }
                    $options = ['choice_type' => $choiceType, 'choices' => $choices];
                }

                $fields[] = [
                    'name'   => $question['text'], 'required' => $question['mandatory'],
                    'weight' => $question['tab_order'], 'options' => $options, 'type' => $type,
                ];
            }
            $form    = ['name' => 'Form_'.$survey['surveyId'], 'maxSubmissions' => 0, 'fields' => $fields];
            $fileId  = $this->ioService->saveFile($form['name'], json_encode($form));
            $forms[] = $fileId;

            // Get the Survey Results
            $sql = 'SELECT sqr.result_id,sqr.question_id,sq.text,sqr.text answer, ';

            $sql .= "CONCAT(sqr.result_id,'-',sqr.question_id) ids, ";
            $sql .= 'COUNT(CONCAT(sqr.result_id,sqr.question_id)) answersCount ';
            $sql .= 'FROM ezsurvey s ';
            $sql .= 'JOIN ezsurveyresult sr ON s.id = sr.survey_id ';
            $sql .= 'JOIN ezsurveyquestionresult sqr ON sr.id = sqr.result_id ';
            $sql .= 'JOIN ezsurveyquestion sq ON sqr.question_id = sq.id ';
            $sql .= 'WHERE s.contentobject_id = ? ';
            $sql .= 'GROUP BY ids ';
            $sql .= 'ORDER BY sqr.result_id,sqr.question_id';

            $results = $this->runQuery($sql, [$survey['contentobject_id']]);
            foreach ($results as $result) {
                $answers = $result['answer'];
                if ($result['answersCount'] > 1) {
                    $sql     = 'SELECT text FROM ezsurveyquestionresult WHERE result_id = ? AND question_id = ?';
                    $answers = $this->runQuery($sql, [$result['result_id'], $result['question_id']], FetchMode::COLUMN);
                }
            }
        }
        $this->ioService->saveFile('manifest', json_encode($forms));

        $io->success('Done.');
    }

    private function runQuery(string $sql, array $parameters = [], $fetchMode = null): array
    {
        $stmt = $this->connection->prepare($sql);
        for ($i = 1, $iMax = count($parameters); $i <= $iMax; ++$i) {
            $stmt->bindValue($i, $parameters[$i - 1]);
        }
        $stmt->execute();

        return $stmt->fetchAll($fetchMode);
    }
}
