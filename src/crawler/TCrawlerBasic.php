<?php

namespace dr4g0nsr;

/**
 * Description of CrawlerBasic
 *
 * @author drago
 */
trait TCrawlerBasic {

    private $settings = [];
    private $temporarySettings = [];
    private $prerequisites = ["curl_init", "mb_language"];

    /**
     * Static function to check version of class
     * 
     * @return string
     */
    public static function version(): string {
        return self::SC_VERSION;
    }

    /**
     * Loads config and merge current one with loaded
     * 
     * It will override settings injected at constructor or default ones if constructor didn't use any settings
     * 
     * @param string $path Path to config.php, if ommited it will use default one from path where tests are, which is probably not what you want
     * @throws \Exception Exception if config does not exists
     */
    public function loadConfig($path = NULL) {
        if (empty($path)) {
            $path = __DIR__ . '/../config.php';
        }
        if (!file_exists($path)) {
            throw new \Exception('Config does not exists');
        }
        require($path);
        foreach ($settings as $setting => $val) {
            $this->temporarySettings[$setting] = $val;
        }
        $this->settings = array_merge($this->temporarySettings, $this->settings);
    }

    /**
     * Return settings stored internally
     * 
     * @return type
     */
    public function getSettings() {
        return $this->settings;
    }

    /**
     * Add message to log
     * 
     * @param mixed $message Message to add to log
     */
    private function log($message) {
        print $message . PHP_EOL;
    }
    
        /**
     * Check if we have basic stuff already installed
     * 
     * @return void
     */
    protected function checkPrerequisites():void {
        foreach ($this->prerequisites as $ext) {
            if (!function_exists($ext)) {
                print "Prerequisites failed: $ext\n";
                die;
            }
        }
    }

}
