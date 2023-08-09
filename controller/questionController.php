<?php

/**
 * Class questionController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class questionController extends Controller
{
    var $question_model = false;

    /**
     * questionController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->question_model = new QuestionModel();
    }

    /**
     * Overview of questions
     */
    public function index()
    {
        $where = ['status' => 'publish'];

        $this->setTotalResults($this->question_model->getRecords($where, [], [] , true));

        $this->set([
            'page_title'       => 'Cooking questions',
            'meta_description' => 'Here you will find answers to cooking questions. We also explain cooking techniques, tips and tricks.',
            'page_canonical'   => Core::url('cooking-questions'),
            'dutch_url'        => 'https://ohmydish.nl/vraag-en-antwoord',
            'questions'        => $this->question_model->getRecords($where, ['id','desc'], $this->getPagination()),
            'pagination'       => $this->getPagination()
        ]);

        $this->render('index');
    }

    /**
     * Question detail.
     */
    public function detail()
    {
        if (!$question = $this->question_model->getBySlug($this->getSlug())) {
            $this->show404();
        } else {
            if (!SessionHelper::hasPermission('is_admin') && ('publish' !== $question['status'])) {
                $this->show404();
            }
        }

        $this->question_model->incrementViews($question);

        $this->set([
            'meta_description'  => (!$question['excerpt'])?$question['title'] . ' Read the answer to this question.':$question['excerpt'],
            'page_title'        => $question['title'] . ' - Cooking questions',
            'page_canonical'    => Core::url('question/' . $question['slug']),
            'og_image'          => TemplateHelper::getFeaturedImage($question),
            'question'          => $question,
            'previous_question' => $this->question_model->getPreviousQuestion($question),
            'next_question'     => $this->question_model->getNextQuestion($question)
        ]);

        $this->render('detail');
    }
}