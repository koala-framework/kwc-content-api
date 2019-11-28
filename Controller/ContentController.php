<?php
namespace Kwc\ContentApiBundle\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Kwf_Component_Data_Root;
use Kwc\ContentApiBundle\Services\ContentBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Kwc\ContentApiBundle\Services\ContentBuilderInterface;

class ContentController
{
    private $contentBuilder;

    public function __construct(ContentBuilderInterface $contentBuilder)
    {
        $this->contentBuilder = $contentBuilder;
    }

    public function dataAction(Request $request)
    {
        if (!$request->get('url') && !$request->get('componentId')) {
            throw new FileNotFoundException();
        }

        $page = false;
        if ($url = $request->get('url')) {
            if (!is_string($url)) {
                throw new Kwf_Exception_NotFound();
            }
            if (substr($url, 0, 1) == '/') {
                $url = 'http://'.$request->getHttpHost().$url;
            }
            $page = Kwf_Component_Data_Root::getInstance()->getPageByUrl($url, null);
        } else if ($componentId = $request->get('componentId')) {
            $page = Kwf_Component_Data_Root::getInstance()->getComponentByDbId($componentId);
        }

        if (!$page) throw new NotFoundHttpException();

        $data = $this->contentBuilder->getContent($page);

        return new JsonResponse(array(
            'data'=>$data
        ), 200);
    }
}
