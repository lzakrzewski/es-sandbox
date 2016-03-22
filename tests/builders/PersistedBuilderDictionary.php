<?php

namespace tests\builders;

/**
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
trait PersistedBuilderDictionary
{
    /**
     * @param PersistentBuilder $builder
     *
     * @return PersistedBuilder
     */
    protected function persisted(PersistentBuilder $builder)
    {
        return new PersistedBuilder($this->container(), $builder);
    }

    protected function thereAre($count, Builder $builder, array $parameters = [])
    {
        foreach (range(1, $count) as $x) {
            foreach ($parameters as $call) {
                list($method, $arguments) = $call;
                $arguments                = is_array($arguments) ? $arguments : [$arguments];
                $builder                  = call_user_func_array([$builder, $method], (array) $arguments);
            }

            $builder->build();
        }
    }
}
