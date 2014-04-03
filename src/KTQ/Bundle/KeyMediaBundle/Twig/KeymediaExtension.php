<?php
/**
 * A port of the old keymedia template-operator to eZ5.
 *
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 03.04.14
 */

namespace KTQ\Bundle\KeyMediaBundle\Twig;

use eZ\Publish\Core\MVC\Legacy\Kernel;
use eZ\Publish\Core\Repository\Values\Content\Content;

class KeymediaExtension extends \Twig_Extension
{
    /** @var Kernel|\Closure */
    protected $legacyKernel;

    public function __construct(\Closure $kernelClosure)
    {
        $this->legacyKernel = $kernelClosure;
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
        // Lazy-loading prevents "InactiveScopeException". It also increases performance.
        if ($this->legacyKernel instanceof \Closure) {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->legacyKernel = $this->legacyKernel->__invoke();
        }

        $objectId = $content->id;
        return $this->legacyKernel->runCallback(function() use($objectId, $identifier, $parameters)
        {
            $object = \eZContentObject::fetch($objectId);
            $attributes = $object->dataMap();

            if (!isset($attributes[$identifier])) {
                throw new \Exception('Field with identifier: '. $identifier .' does not exist.');
            }

            $parameters['attribute'] = $attributes[$identifier];
            $parameters += array('quality' => false);
            $result = null;

            $operator = new \TemplateKeymediaOperator();
            $operator->modify('', 'keymedia', null, null, null, $result, $parameters, null);

            return $result;
        });
    }
}