<?php

namespace PlaygroundCore\Service\Factory;

use PlaygroundCore\Options\ModuleOptions;
use Zend\ServiceManager\Exception\InvalidArgumentException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class PhpvideotoolkitServiceFactory
 *
 * @package PlaygroundCore\Service\Factory
 */
class PhpvideotoolkitServiceFactory implements FactoryInterface
{
    
    /**
     * @var PlaygroundCoreOptionsInterface
     */
    protected $options;
    
    /**
     * Generates the Item controller
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return \PHPVideoToolkit\FfmpegProcess
     */
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        /**
         * @var ServiceManager $serviceManager
         * @var ModuleOptions $options
         */
        $options = $serviceManager->get('playgroundcore_module_options');
        $phpvideotoolkitOptions = $options->getPhpvideotoolkit();
        
        if (!isset($phpvideotoolkitOptions['ffmpeg']) || $phpvideotoolkitOptions['ffmpeg'] === '') {
            throw new InvalidArgumentException('No phpvideotoolkit configuration found');
        }

        try
        {
            $config = new \PHPVideoToolkit\Config($phpvideotoolkitOptions);
        }
        catch(\PHPVideoToolkit\Exception $e)
        {
            throw new InvalidArgumentException('phpvideotoolkit error during configuration load');
            // \PHPVideoToolkit\Trace::vars($e);
        }
        
        try
        {
            $service = new \PHPVideoToolkit\FfmpegProcess('ffmpeg', $config);
        }
        catch(Exception $e)
        {
            throw new InvalidArgumentException('phpvideotoolkit process creation error');
            // \PHPVideoToolkit\Trace::vars($e->getMessage());
            // \PHPVideoToolkit\Trace::vars($e);
        }

        return $service;
    }
}