<?php

declare(strict_types = 1);

namespace Plugin\ImageProcessing\Routes\Api;

use League\Flysystem\Filesystem;
use Plugin\ImageProcessing\Service\ImageProcessor\GlideImageProcessor;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ImageProcessingController {
	/**
	 * @var Filesystem
	 */
	protected $files;

	/**
	 * @var GlideImageProcessor
	 */
	protected $glideImageProcessor;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		$this->files               = $container->files;
		$this->glideImageProcessor = $container->glideImageProcessor;
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
	public function processImageAction(Request $request, Response $response, array $args): Response {
		$filename = $args['filename'];

		$glideServer = $this->glideImageProcessor->createGlideServer(
			$this->files,
			$this->files
		);

		return $glideServer->getImageResponse(
			$filename,
			$this->buildGlideImageProcessingParameters($request)
		);
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildGlideImageProcessingParameters(Request $request): array {
		return array_merge(
			$this->buildSizeParameters($request),
			$this->buildOrientationParameters($request),
			$this->buildFlipParameters($request),
			$this->buildCropParameters($request),
			$this->buildDevicePixelRatioParameters($request),
			$this->buildAdjustmentParameters($request),
			$this->buildEffectParameters($request),
			$this->buildWatermarkParameters($request),
			$this->buildBackgroundParameters($request),
			$this->buildBorderParameters($request),
			$this->buildEncodeParameters($request)
		);
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildSizeParameters(Request $request): array {
		$parameters = [];

		$width  = $request->getQueryParams()['w'] ?? null;
		$height = $request->getQueryParams()['h'] ?? null;
		$fit    = $request->getQueryParams()['fit'] ?? null;

		if ($width !== null) {
			$parameters['w'] = $width;
		}

		if ($height !== null) {
			$parameters['h'] = $height;
		}

		if ($fit !== null) {
			$parameters['fit'] = $fit;
		}

		return $parameters;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildOrientationParameters(Request $request): array {
		$parameters = [];

		$orientation = $request->getQueryParams()['or'] ?? null;

		if ($orientation !== null) {
			$parameters['or'] = $orientation;
		}

		return $parameters;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildFlipParameters(Request $request): array {
		$parameters = [];

		$flip = $request->getQueryParams()['flip'] ?? null;

		if ($flip !== null) {
			$parameters['flip'] = $flip;
		}

		return $parameters;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildCropParameters(Request $request): array {
		$parameters = [];

		$crop = $request->getQueryParams()['crop'] ?? null;

		if ($crop !== null) {
			$parameters['crop'] = $crop;
		}

		return $parameters;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildDevicePixelRatioParameters(Request $request): array {
		$parameters = [];

		$devicePixelRatio = $request->getQueryParams()['dpr'] ?? null;

		if ($devicePixelRatio !== null) {
			$parameters['dpr'] = $devicePixelRatio;
		}

		return $parameters;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildAdjustmentParameters(Request $request): array {
		$parameters = [];

		$brightness = $request->getQueryParams()['bri'] ?? null;
		$contrast   = $request->getQueryParams()['con'] ?? null;
		$gamma      = $request->getQueryParams()['gam'] ?? null;
		$sharpen    = $request->getQueryParams()['sharp'] ?? null;;

		if ($brightness !== null) {
			$parameters['bri'] = $brightness;
		}

		if ($contrast !== null) {
			$parameters['con'] = $contrast;
		}

		if ($gamma !== null) {
			$parameters['gam'] = $gamma;
		}

		if ($sharpen !== null) {
			$parameters['sharp'] = $sharpen;
		}

		return $parameters;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildEffectParameters(Request $request): array {
		$parameters = [];

		$blur     = $request->getQueryParams()['blur'] ?? null;
		$pixelate = $request->getQueryParams()['pixel'] ?? null;
		$filter   = $request->getQueryParams()['filt'] ?? null;

		if ($blur !== null) {
			$parameters['blur'] = $blur;
		}

		if ($pixelate !== null) {
			$parameters['pixel'] = $pixelate;
		}

		if ($filter !== null) {
			$parameters['filt'] = $filter;
		}

		return $parameters;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildWatermarkParameters(Request $request): array {
		$parameters = [];

		$path     = $request->getQueryParams()['mark'] ?? null;
		$width    = $request->getQueryParams()['markw'] ?? null;
		$height   = $request->getQueryParams()['markh'] ?? null;
		$fit      = $request->getQueryParams()['markfit'] ?? null;
		$offsetX  = $request->getQueryParams()['markx'] ?? null;
		$offsetY  = $request->getQueryParams()['marky'] ?? null;
		$padding  = $request->getQueryParams()['markpad'] ?? null;
		$position = $request->getQueryParams()['markpos'] ?? null;
		$alpha    = $request->getQueryParams()['markalpha'] ?? null;

		if ($path !== null) {
			$parameters['mark'] = $path;
		}

		if ($path !== null) {
			$parameters['markw'] = $path;
		}

		if ($path !== null) {
			$parameters['markh'] = $path;
		}

		if ($path !== null) {
			$parameters['markfit'] = $path;
		}

		if ($path !== null) {
			$parameters['markx'] = $path;
		}

		if ($path !== null) {
			$parameters['marky'] = $path;
		}

		if ($path !== null) {
			$parameters['markpad'] = $path;
		}

		if ($path !== null) {
			$parameters['markpos'] = $path;
		}

		if ($path !== null) {
			$parameters['markalpha'] = $path;
		}

		return $parameters;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildBackgroundParameters(Request $request): array {
		$parameters = [];

		$background = $request->getQueryParams()['bg'] ?? null;

		if ($background !== null) {
			$parameters['bg'] = $background;
		}

		return $parameters;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildBorderParameters(Request $request): array {
		$parameters = [];

		$border = $request->getQueryParams()['border'] ?? null;

		if ($border !== null) {
			$parameters['border'] = $border;
		}

		return $parameters;
	}

	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	protected function buildEncodeParameters(Request $request): array {
		$parameters = [];

		$quality = $request->getQueryParams()['q'] ?? null;
		$format  = $request->getQueryParams()['fm'] ?? null;

		if ($quality !== null) {
			$parameters['q'] = $quality;
		}

		if ($format !== null) {
			$parameters['fm'] = $format;
		}

		return $parameters;
	}
}
