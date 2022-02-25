<?php

namespace HealthChecker\Tools;

class Template
{
    protected $blocks = [];
    protected $view_path = '';
    protected $cache_path = '';
    protected $cache_enabled = false;

    public function __construct($view_path = 'views', $cache_path = 'cache', $cache_enabled = false)
    {
        $this->view_path = $view_path;
        $this->cache_path = $cache_path;
        $this->cache_enabled = $cache_enabled;
    }

    public function view($file, $data = [])
    {
        $cached_file = $this->cache($file);

        ob_start();
        extract($data, EXTR_SKIP);
        require $cached_file;
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    protected function cache($file)
    {
        if (!file_exists($this->cache_path)) {
            mkdir($this->cache_path, 0744);
        }
        $cached_file = $this->cache_path . "/" . str_replace([$this->view_path . "/", '/', '.html'], ['', '_', ''], $file . '.php');
        if (!$this->cache_enabled || !file_exists($cached_file) || filemtime($cached_file) < filemtime($file . '.php')) {
            $code = $this->includeFiles($file);
            $code = $this->compileCode($code);
            file_put_contents($cached_file, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);
        }
        return $cached_file;
    }

    protected function clearCache()
    {
        foreach (glob($this->cache_path . '*') as $file) {
            unlink($file);
        }
    }

    protected function compileCode($code)
    {
        $code = $this->compileBlock($code);
        $code = $this->compileYield($code);
        $code = $this->compileEscapedEchos($code);
        $code = $this->compileEchos($code);
        $code = $this->compilePHP($code);
        return $code;
    }

    protected function includeFiles($file)
    {
        $code = file_get_contents($file . ".php");
        preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], $this->includeFiles($this->view_path . '/' . $value[2]), $code);
        }
        $code = preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
        return $code;
    }

    protected function compilePHP($code)
    {
        return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $code);
    }

    protected function compileEchos($code)
    {
        return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo $1 ?>', $code);
    }

    protected function compileEscapedEchos($code)
    {
        return preg_replace('~\{{{\s*(.+?)\s*\}}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $code);
    }

    protected function compileBlock($code)
    {
        preg_match_all('/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            if (!array_key_exists($value[1], $this->blocks)) $this->blocks[$value[1]] = '';
            if (strpos($value[2], '@parent') === false) {
                $this->blocks[$value[1]] = $value[2];
            } else {
                $this->blocks[$value[1]] = str_replace('@parent', $this->blocks[$value[1]], $value[2]);
            }
            $code = str_replace($value[0], '', $code);
        }
        return $code;
    }

    protected function compileYield($code)
    {
        foreach ($this->blocks as $block => $value) {
            $code = preg_replace('/{% ?yield ?' . $block . ' ?%}/', $value, $code);
        }
        $code = preg_replace('/{% ?yield ?(.*?) ?%}/i', '', $code);
        return $code;
    }
}
