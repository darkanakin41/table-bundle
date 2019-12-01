<?php

/*
 * This file is part of the Darkanakin41TableBundle package.
 */

namespace Darkanakin41\TableBundle\Filter;

use Darkanakin41\TableBundle\Definition\Field;
use Darkanakin41\TableBundle\Definition\Filter;

class DateTimeFilter extends Filter
{
    /**
     * @var string
     */
    private $operator;
    /**
     * @var bool
     */
    private $or_null;

    public function __construct(Field $field, $value, $operator = '=', $or_null = false)
    {
        parent::__construct($field, $value, false);
        $this->setOperator($operator);
        $this->setOrNull($or_null);
    }

    public function isOrNull(): bool
    {
        return $this->or_null;
    }

    public function setOrNull(bool $or_null): DateTimeFilter
    {
        $this->or_null = $or_null;

        return $this;
    }

    public function getDQL($key, $alias)
    {
        switch ($this->getOperator()) {
            default:
                $first_part = sprintf('%s %s :%s ', $this->getField()->getDQL($alias), $this->getOperator(), $this->getAlias($key));
                if ($this->isOrNull()) {
                    return sprintf('(%s OR %s IS NULL)', $first_part, $this->getField()->getDQL($alias));
                }

                return $first_part;
            case 'end_of_day':
                return sprintf('%s BETWEEN :%s AND :%s', $this->getField()->getDQL($alias), $this->getAlias($key).'_start', $this->getAlias($key).'_end');
        }
    }

    /**
     * @param string $key
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getDQLParameters($key)
    {
        switch ($this->getOperator()) {
            default:
                return parent::getDQLParameters($key);
            case 'end_of_day':
                $start = $this->getValue();
                $end = clone $start;
                $end->add(new \DateInterval('P1D'));
                $end->setTime(0, 0);

                return array(
                    $this->getAlias($key).'_start' => $start,
                    $this->getAlias($key).'_end' => $end,
                );
        }
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function setOperator(string $operator): DateTimeFilter
    {
        $this->operator = $operator;

        return $this;
    }
}
