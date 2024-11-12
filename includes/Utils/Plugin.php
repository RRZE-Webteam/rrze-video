<?php

namespace RRZE\Video\Utils;

defined('ABSPATH') || exit;

class Plugin
{
    protected $pluginFile;
    protected $basename;
    protected $directory;
    protected $url;
    protected $version;

    public function __construct(string $pluginFile)
    {
        $this->pluginFile = $pluginFile;
    }

    public function loaded()
    {
        $this->setBasename()
            ->setDirectory()
            ->setUrl()
            ->setVersion();
    }

    public function getFile(): string
    {
        return $this->pluginFile;
    }

    public function getBasename(): string
    {
        return $this->basename;
    }

    public function setBasename(): object
    {
        $this->basename = plugin_basename($this->pluginFile);
        return $this;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function setDirectory(): object
    {
        $directory = plugin_dir_path($this->pluginFile);
        $this->directory = $directory !== null ? rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '';
        return $this;
    }

    public function getPath(string $path = ''): string
    {
        $path = $path ?? '';
        return $this->directory . ltrim((string)$path, DIRECTORY_SEPARATOR);
    }
    

    public function getUrl(string $path = ''): string
    {
        $path = $path ?? '';
        return $this->url . ltrim((string)$path, DIRECTORY_SEPARATOR);
    }
    

    public function setUrl(): object
    {
        $url = plugin_dir_url($this->pluginFile);
        $this->url = $url !== null ? rtrim($url, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '';
        return $this;
    }

    public function getSlug(): string
    {
        return sanitize_title(dirname($this->basename));
    }

    public function getVersion(): string
    {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            return bin2hex(random_bytes(4));
        }
        return $this->version;
    }

    public function setVersion(): object
    {
        $headers = ['Version' => 'Version'];
        $fileData = get_file_data($this->pluginFile, $headers, 'plugin');
        if (isset($fileData['Version'])) {
            $this->version = $fileData['Version'];
        }
        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        if (!method_exists($this, $name)) {
            $message = sprintf('Call to undefined method %1$s::%2$s', __CLASS__, $name);
            do_action(
                'rrze.log.error',
                $message,
                [
                    'class' => __CLASS__,
                    'method' => $name,
                    'arguments' => $arguments
                ]
            );
            if (defined('WP_DEBUG') && WP_DEBUG) {
                throw new \Exception($message);
            }
        }
    }
}
