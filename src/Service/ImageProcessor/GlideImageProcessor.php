<?php

declare(strict_types = 1);

namespace Plugin\ImageProcessing\Service\ImageProcessor;

use Intervention\Image\ImageManager;
use League\Flysystem\Filesystem;
use League\Glide\Api\Api;
use League\Glide\Manipulators\Blur;
use League\Glide\Manipulators\Crop;
use League\Glide\Manipulators\Size;
use League\Glide\Manipulators\Gamma;
use League\Glide\Manipulators\Border;
use League\Glide\Manipulators\Encode;
use League\Glide\Manipulators\Filter;
use League\Glide\Manipulators\Sharpen;
use League\Glide\Manipulators\Contrast;
use League\Glide\Manipulators\Pixelate;
use League\Glide\Manipulators\Watermark;
use League\Glide\Manipulators\Background;
use League\Glide\Manipulators\Brightness;
use League\Glide\Manipulators\Orientation;
use League\Glide\Responses\PsrResponseFactory;
use League\Glide\Server;
use Slim\Http\Stream;
use Slim\Http\Response;

class GlideImageProcessor {
	/**
	 * @var string
	 */
	protected $gdMemoryLimit;

	/**
	 * @var string
	 */
	protected $driver;

	/**
	 * @var int
	 */
	protected $imageResizeLimit;

	/**
	 * @var string
	 */
	protected $cachePathPrefix;

	/**
	 * @var array
	 */
	protected $presets;

	/**
	 * @param string $gdMemoryLimit
	 * @param string $driver
	 * @param int $imageResizeLimit
	 * @param string $cachePathPrefix
	 * @param array $presets
	 */
	public function __construct(
		string $gdMemoryLimit,
		string $driver,
		int $imageResizeLimit,
		string $cachePathPrefix,
		array $presets
	) {
		$this->gdMemoryLimit    = $gdMemoryLimit;
		$this->driver           = $driver;
		$this->imageResizeLimit = $imageResizeLimit;
		$this->cachePathPrefix  = $cachePathPrefix;
		$this->presets          = $presets;
	}

	/**
	 * @param Filesystem $sourceFilesystem
	 * @param Filesystem $cacheFilesystem
	 * @param Filesystem|null $watermarksFilesystem
	 *
	 * @return Server
	 */
	public function createGlideServer(
		Filesystem $sourceFilesystem,
		Filesystem $cacheFilesystem,
		?Filesystem $watermarksFilesystem = null
	): Server {
		$imageManager = $this->createImageManager();
		$manipulators = $this->composeManipulators($watermarksFilesystem);

		$api = new Api($imageManager, $manipulators);

		$server = new Server($sourceFilesystem, $cacheFilesystem, $api);

		$server->setResponseFactory(
			new PsrResponseFactory(
				new Response(),
				function ($stream) {
		            return new Stream($stream);
		        }
			)
		);

		$server->setCachePathPrefix($this->cachePathPrefix);
		$server->setPresets($this->presets);

		return $server;
	}

	/**
	 * @return ImageManager
	 */
	protected function createImageManager(): ImageManager {
		// Try to increase memory limit for big images
		if ($this->driver === 'gd') {
			ini_set('memory_limit', $this->gdMemoryLimit);
		}

		return new ImageManager(['driver' => $this->driver]);
	}

	/**
	 * @param Filesystem|null $watermarksFilesystem
	 *
	 * @return array
	 */
	protected function composeManipulators(?Filesystem $watermarksFilesystem): array {
		$manipulators = [
			new Orientation(),
			new Crop(),
			new Size($this->imageResizeLimit),
			new Brightness(),
			new Contrast(),
			new Gamma(),
			new Sharpen(),
			new Filter(),
			new Blur(),
			new Pixelate(),
			new Background(),
			new Border(),
			new Encode()
		];

		if ($watermarksFilesystem !== null) {
			$manipulators[] = new Watermark($watermarksFilesystem);
		}

		return $manipulators;
	}
}
