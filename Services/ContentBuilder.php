<?php
namespace Kwc\ContentApiBundle\Services;

use Kwc_Abstract;
use Kwf_Component_Data;

class ContentBuilder implements ContentBuilderInterface
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
            $contentData = $apiContent->getContent($data);
            if ($contentData instanceof Kwf_Component_Data) {
                $ret = $this->getContent($contentData);
            } else {
                $ret['type'] = Kwc_Abstract::getSetting($data->componentClass, 'apiContentType');
                $ret['id'] = $data->componentId;
                if ($data = $this->convertData($contentData)) {
                    $ret['data'] = $data;
                }
            }
        } else {
            $ret = array();
            $ret['data'] = array(
                'html' => $data->render()
            );
            $ret['type'] = 'legacyHtml';
            $ret['id'] = $data->componentId;
            $ret['componentClass'] = $data->componentClass;
        }
        return $ret;
    }

    private function convertData($data)
    {
        if ($data === null) return null;

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
