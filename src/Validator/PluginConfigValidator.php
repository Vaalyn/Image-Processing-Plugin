<?php

declare(strict_types = 1);

namespace Plugin\ImageProcessing\Validator;

use CashewCRM\Exception\PluginConfigValidationException;
use CashewCRM\Service\Plugin\PluginConfigValidatorInterface;
use Plugin\ImageProcessing\Constants\PluginConfigConstants;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class PluginConfigValidator implements PluginConfigValidatorInterface {
	/**
	 * @inheritDoc
	 */
	public function validate(array $config): void {
		try {
			$this->validateConfig($config);
		}
		catch(NestedValidationException $exception) {
			$pluginConfigValidationException = new PluginConfigValidationException();

			foreach ($exception->getMessages() as $validationError) {
				$pluginConfigValidationException->addValidationError($validationError);
			}

			throw $pluginConfigValidationException;
		}
	}

	/**
	 * @param array $config
	 *
	 * @return PluginConfigValidator
	 *
	 * @throws NestedValidationException
	 */
	protected function validateConfig(array $config): PluginConfigValidator {
		Validator::keyNested(
				PluginConfigConstants::CACHE_PATH_PREFIX,
				Validator::stringType()
			)
			->keyNested(
				PluginConfigConstants::DRIVER,
				Validator::stringType()
			)
			->keyNested(
				PluginConfigConstants::GD_SET_MEMORY_LIMIT,
				Validator::stringType()
			)
			->keyNested(
				PluginConfigConstants::IMAGE_RESIZE_LIMIT,
				Validator::intType()
			)
			->keyNested(
				PluginConfigConstants::PRESETS,
				Validator::arrayType()
			)
			->assert($config);

		return $this;
	}
}
