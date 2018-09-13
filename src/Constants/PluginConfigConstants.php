<?php

declare(strict_types = 1);

namespace Plugin\ImageProcessing\Constants;

interface PluginConfigConstants {
	public const CACHE_PATH_PREFIX   = 'cachePathPrefix';
	public const DRIVER              = 'driver';
	public const GD_SET_MEMORY_LIMIT = 'gdSetMemoryLimit';
	public const IMAGE_RESIZE_LIMIT  = 'imageResizeLimit';
	public const PRESETS             = 'presets';
}
