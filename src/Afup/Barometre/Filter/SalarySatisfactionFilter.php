<?php

namespace Afup\Barometre\Filter;

use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\DBAL\Query\QueryBuilder;

use Afup\BarometreBundle\Enums\SalarySatisfactionEnums;
use Afup\Barometre\Form\Type\Select2MultipleFilterType;

class SalarySatisfactionFilter implements FilterInterface
{
    /**
     * @var SalarySatisfactionEnums
     */
    private $salarySatisfaction;

    /**
     * @param SalarySatisfactionEnums $salarySatisfaction
     */
    public function __construct(SalarySatisfactionEnums $salarySatisfaction)
    {
        $this->salarySatisfaction = $salarySatisfaction;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder->add($this->getName(), Select2MultipleFilterType::class, [
            'label'    => 'filter.salary_satisfaction',
            'choices'  => array_flip($this->salarySatisfaction->getChoices()),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(QueryBuilder $queryBuilder, array $values = array())
    {
        if (!array_key_exists($this->getName(), $values) || 0 === count($values[$this->getName()])) {
            return;
        }

        $queryBuilder
            ->andWhere($queryBuilder->expr()->in('response.salarySatisfaction', $values[$this->getName()]));
    }

    /**
     * {@inheritdoc}
     */
    public function convertValuesToLabels($value)
    {
        return array_map(function ($value) {
            return $this->salarySatisfaction->getLabelById($value);
        }, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'salary_satisfaction';
    }

    /**
     * Filter weight
     *
     * @return int
     */
    public function getWeight()
    {
        return 10;
    }
}
