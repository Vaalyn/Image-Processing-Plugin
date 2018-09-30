<?php

declare(strict_types = 1);

namespace Plugin\ImageProcessing;

use Plugin\ImageProcessing\Constants\PluginConfigConstants;
use Plugin\ImageProcessing\Routes\Api;
use Plugin\ImageProcessing\Service\ImageProcessor\GlideImageProcessor;
use Plugin\ImageProcessing\Validator\PluginConfigValidator;
use Psr\Container\ContainerInterface;
use Slim\App;
use Vaalyn\PluginService\AbstractPlugin;

class ImageProcessingPlugin extends AbstractPlugin {
	protected const PLUGIN_NAME = 'image-processing-plugin';

	/**
	 * @inheritDoc
	 */
	public static function getPluginName(): string {
		return self::PLUGIN_NAME;
	}

	/**
	 * @inheritDoc
	 */
	public static function getPluginPath(): string {
		return __DIR__ . '/..';
	}

	/**
	 * @inheritDoc
	 */
	public function load(ContainerInterface $container): void {
		$this->loadConfiguration($container);

		$this->loadPluginConfig(
			$container,
			new PluginConfigValidator()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function registerServices(ContainerInterface $container): void {
		$pluginConfigs                  = $container->config[self::PLUGIN_CONFIGS_NAME];
		$imageProcessingPluginConfigs   = $pluginConfigs[self::getPluginName()];

		$container->glideImageProcessor = new GlideImageProcessor(
			$imageProcessingPluginConfigs[PluginConfigConstants::GD_SET_MEMORY_LIMIT],
			$imageProcessingPluginConfigs[PluginConfigConstants::DRIVER],
			$imageProcessingPluginConfigs[PluginConfigConstants::IMAGE_RESIZE_LIMIT],
			$imageProcessingPluginConfigs[PluginConfigConstants::CACHE_PATH_PREFIX],
			$imageProcessingPluginConfigs[PluginConfigConstants::PRESETS]
		);
	}

	/**
	 * @inheritDoc
	 */
	public function registerMiddlewares(App $app, ContainerInterface $container): void {
	}

	/**
	 * @inheritDoc
	 */
	public function registerRoutes(App $app, ContainerInterface $container): void {
		$app->group('/api/plugin/image/processing', function() {
			$this->get('/process/{filename:.*}', Api\ImageProcessingController::class . ':processImageAction')->setName('api.plugin.image.processor.process');
		});
	}
}
