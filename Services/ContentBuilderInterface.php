<?php
namespace Kwc\ContentApiBundle\Services;

use Kwf_Component_Data;

interface ContentBuilderInterface
{
    public function getContent(Kwf_Component_Data $data);
}
