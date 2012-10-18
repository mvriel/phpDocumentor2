<?php

namespace phpDocumentor\Transformer\Template;

class Factory
{
    /** @var \phpDocumentor\Transformer\Template[] */
    protected $templates = array();

    /** @var string */
    protected $path;

    /**
     * Sets the path where the templates are located.
     *
     * @param string $path Absolute path where the templates are.
     *
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Returns the path where the templates are located.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getTemplate($name)
    {
        // if the template is already loaded we do not reload it.
        if (isset($this->templates[$name])) {
            return $this->templates[$name];
        }

        $path = null;

        // if this is an absolute path; load the template into the configuration
        // Please note that this _could_ override an existing template when
        // you have a template in a subfolder with the same name as a default
        // template; we have left this in on purpose to allow people to override
        // templates should they choose to.
        $config_path = rtrim($name, DIRECTORY_SEPARATOR) . '/template.xml';
        if (file_exists($config_path) && is_readable($config_path)) {
            $path = rtrim($name, DIRECTORY_SEPARATOR);
            $template_name_part = basename($path);
            $cache_path = rtrim($this->getTemplatesPath(), '/\\')
            . DIRECTORY_SEPARATOR . $template_name_part;

            // move the files to a cache location and then change the path
            // variable to match the new location
            $this->copyRecursive($path, $cache_path);
            $path = $cache_path;

            // transform all directory separators to underscores and lowercase
            $name = strtolower(
                str_replace(
                    DIRECTORY_SEPARATOR,
                    '_',
                    rtrim($name, DIRECTORY_SEPARATOR)
                )
            );
        }

        // if we load a default template
        if ($path === null) {
            $path = rtrim($this->getPath(), '/\\')
                . DIRECTORY_SEPARATOR . $name;
        }

        if (!file_exists($path) || !is_readable($path)) {
            throw new \InvalidArgumentException(
                'The given template ' . $name.' could not be found or is not '
                . 'readable'
            );
        }

        // track templates to be able to refer to them later
        $this->templates[$name] = new \phpDocumentor\Transformer\Template();
        $this->templates[$name]->setName($name);

        $reader = new Reader\Xml($this->templates[$name]);
        $reader->process(
            file_get_contents($path . DIRECTORY_SEPARATOR . 'template.xml')
        );

        return $this->templates[$name];
    }

    /**
     * Copies a file or folder recursively to another location.
     *
     * @param string $src The source location to copy
     * @param string $dst The destination location to copy to
     *
     * @throws \Exception if $src does not exist or $dst is not writable
     *
     * @return void
     */
    public function copyRecursive($src, $dst)
    {
        // if $src is a normal file we can do a regular copy action
        if (is_file($src)) {
            copy($src, $dst);
            return;
        }

        $dir = opendir($src);
        if (!$dir) {
            throw new \Exception('Unable to locate path "' . $src . '"');
        }

        // check if the folder exists, otherwise create it
        if ((!file_exists($dst)) && (false === mkdir($dst))) {
            throw new \Exception('Unable to create folder "' . $dst . '"');
        }

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->copyRecursive($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
