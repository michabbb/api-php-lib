<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\User;

class Info extends \PleskX\Api\Struct
{
    /** @var integer */
    public $id;

    /** @var string */
    public $guid;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'guid',
        ]);
    }
}