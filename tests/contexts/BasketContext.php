<?php

namespace tests\contexts;

use tests\builders\PersistedBuilderDictionary;
use tests\common\CommandBusDictionary;

class BasketContext extends DefaultContext
{
    use PersistedBuilderDictionary;
    use CommandBusDictionary;
}
