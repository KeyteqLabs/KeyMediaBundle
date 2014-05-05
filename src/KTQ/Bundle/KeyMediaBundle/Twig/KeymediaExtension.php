<?php
/**
 * A port of the old keymedia template-operator to eZ5.
 *
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 03.04.14
 */

namespace KTQ\Bundle\KeyMediaBundle\Twig;

use eZ\Publish\Core\Helper\TranslationHelper;
use eZ\Publish\Core\MVC\Legacy\Kernel;
use eZ\Publish\Core\Repository\Values\Content\Content;

class KeymediaExtension extends \Twig_Extension
{
    /** @var Kernel|\Closure */
    protected $legacyKernel;
    /** @var TranslationHelper */
    protected $translationHelper;

    public function __construct(\Closure $kernelClosure, TranslationHelper $translationHelper)
    {
        $this->legacyKernel = $kernelClosure;
        $this->translationHelper = $translationHelper;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'KeyteqLabs:KeyMedia';
    }

    public function getFunctions()
    {
        return array(new \Twig_SimpleFunction('keymedia', array($this, 'keyMedia')));
    }

    public function keyMedia(Content $content, $identifier, $parameters = array())
    {
        $field = $this->translationHelper->getTranslatedField($content, $identifier);
        if (!$field) {
            throw new \Exception("Field with identifier: {$identifier} does not exist.");
        }

        // Lazy-loading prevents "InactiveScopeException". It also increases performance.
        if ($this->legacyKernel instanceof \Closure) {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->legacyKernel = $this->legacyKernel->__invoke();
        }

        $attributeId = $field->id;
        $version = $content->versionInfo->versionNo;
        return $this->legacyKernel->runCallback(function() use($attributeId, $version, $parameters)
        {
            if (!$attribute = \eZContentObjectAttribute::fetch($attributeId, $version)) {
                throw new \Exception("Field with id: {$attributeId} does not exist for version: {$version}.");
            }

            $parameters['attribute'] = $attribute;
            $parameters += array('quality' => false);
            $result = null;

            $operator = new \TemplateKeymediaOperator();
            $operator->modify('', 'keymedia', null, null, null, $result, $parameters, null);

            return $result;
        });
    }
}