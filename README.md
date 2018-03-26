# Content API

## Installation
* Add AppBundle to `AppKernel.php`

        public function registerBundles()
        {
            $bundles = array(
                ...
                new Kwf\KwcContentApiBundle\KwfKwcContentApiBundle()
            );
            ...
        }

* Add routing config to `routing.yml`

        kwccontentapi:
            resource: "@KwfKwcContentApiBundle/Resources/config/routing.yml"
            prefix:   /

* Add components to be exported to whitelist in `config.yml`


    kwc_content_api:
        export_components:
            - Kwc_Paragraphs_Component
            - Kwc_Basic_Headline_Component

