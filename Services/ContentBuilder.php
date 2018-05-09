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

    public function getContent(Kwf_Component_Data $data)
    {
        if (Kwc_Abstract::hasSetting($data->componentClass, 'apiContent') && in_array($data->componentClass, $this->exportComponents)) {
            $cls = Kwc_Abstract::getSetting($data->componentClass, 'apiContent');
            $apiContent = new $cls();
            $ret = $apiContent->getContent($data);
            if ($ret instanceof Kwf_Component_Data) {
                $ret = $this->getContent($ret);
            } else {
                $ret['type'] = Kwc_Abstract::getSetting($data->componentClass, 'apiContentType');
            }


            $ret = $this->convertData($ret);
        } else {
            $ret = array();
            $ret['html'] = $data->render();
            $ret['type'] = 'legacyHtml';
        }
        return $ret;
    }

    private function convertData($data)
    {
        foreach ($data as $k=>$i) {
            if (is_array($i)) {
                $data[$k] = $this->convertData($i);
            } else if ($i instanceof Kwf_Component_Data) {
                $data[$k] = $this->getContent($i);
            } else if (is_object($i)) {
                $data[$k] = $this->convertData((array)$i);
            } else {
                $data[$k] = $i;
            }
        }
        return $data;
    }
}
