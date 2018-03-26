<?php
namespace Kwc\ContentApiBundle\Services;

use Kwc_Abstract;
use Kwf_Component_Data;

class ContentBuilder
{
    private $exportComponents;

    public function setExportComponents(array $componentClasses)
    {
        $this->exportComponents = $componentClasses;
    }

    public function getContent(\Kwf_Component_Data $data)
    {
        if (Kwc_Abstract::hasSetting($data->componentClass, 'apiContent') && in_array($data->componentClass, $this->exportComponents)) {
            $cls = Kwc_Abstract::getSetting($data->componentClass, 'apiContent');
            $apiContent = new $cls();
            $ret = $apiContent->getContent($data);
            $ret['type'] = Kwc_Abstract::getSetting($data->componentClass, 'apiContentType');

            $self = $this;
            array_walk_recursive($ret, function(&$v) use ($self) {
                if ($v instanceof Kwf_Component_Data) {
                    $v = $self->getContent($v);
                }
            });
        } else {
            $ret = array();
            $ret['html'] = $data->render();
            $ret['type'] = 'legacyHtml';
            $ret['cls'] = $data->componentClass;
        }
        return $ret;
    }
}
