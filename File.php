<?php

/**
 * Main docblock
 *
 * PHP version 5
 *
 * @category  Cache
 * @package   CacheUtil
 * @author    Edouard Kombo <edouard.kombo@gmail.com>
 * @copyright 2013-2014 Edouard Kombo
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @version   GIT: 1.0.0
 * @link      http://www.breezeframework.com/thetrollinception.php
 * @since     1.0.0
 */
namespace TTI\CacheUtil;

use TTI\AbstractFactory\HandleAbstraction;

/**
 * File driver responsibility is to handle cache via file.
 *
 * @category Cache
 * @package  CacheUtil
 * @author   Edouard Kombo <edouard.kombo@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://www.breezeframework.com/thetrollinception.php
 */
class File extends HandleAbstraction
{
    /**
     *
     * @var int $expire Time for expiration
     */
    protected $expire = 3600;
    
    /**
     *
     * @var string $dir Cache directory
     */
    protected $dir;    
    
    /**
     * Constructor
     */
    public function __construct()
    {      
    }

    /**
     * Cloner
     * 
     * @return void
     */
    public function __clone()
    {
    }      
    
    /**
     * Cache directory
     *
     * @param string $dir Cache directory
     * 
     * @return \TTI\CacheUtil\File
     */    
    protected function dir($dir)
    {
        $this->dir = ($dir === null) ? '' : $dir ;
        return (object) $this;
    }
    
    /**
     * Refresh directory
     * 
     * @return \TTI\CacheUtil\File
     */    
    protected function refresh()
    {
        $files = (array) glob($this->dir . 'cache.*');
        
        if ($files) {
            foreach ($files as $file) {
                $time = (integer) substr(strrchr($file, '.'), 1);
                if (($time < time()) && file_exists($file)) {
                    unlink($file);
                }
            } 
        }
        
        return (object) $this;
    }    

    /**
     * Time for expiration
     *
     * @param string $time Time for expiration
     * 
     * @return \TTI\CacheUtil\File
     */
    protected function expire($time)
    {
        $this->expire = ($time === null) ? $this->expire : $time ;
        return (object) $this;
    }    
    
    /**
     * Get the final value
     *
     * @param string $key key to retrieve
     * 
     * @throws RuntimeException
     * @return string
     */
    public function get($key)
    {
        $regular = (string) preg_replace('/[^A-Z0-9\._-]/i', '', $key);
        $files = (array) glob($this->dir . 'cache.' . $regular . '.*');
        
        try{
            if (!$files) {
                throw new \RuntimeException("No files matching!");
            }
            $handle = fopen($files[0], 'r');
            $cache = fread($handle, filesize($files[0]));
            fclose($handle);
            return (string) unserialize($cache);
        } catch (\RuntimeException $ex) {
            echo $ex->getMessage();
            exit();
        }
    }

    /**
     * Set a value in the cache
     *
     * @param string $key   key to assign
     * @param mixed  $value We assign whatever we want to key
     * 
     * @return \TTI\CacheUtil\File
     */
    public function set($key, $value)
    {
        $this->delete($key);

        $regular = (string) preg_replace('/[^A-Z0-9\._-]/i', '', $key);
        $time = (integer) time() + $this->expire;
        $file = (string) $this->dir . 'cache.' . $regular . '.' . $time;

        $handle = fopen($file, 'w');
        fwrite($handle, serialize($value));
        fclose($handle);
        
        return (object) $this;
    }

    /**
     * Delete an entry from cache
     * 
     * @param string $key Key to delete from cache
     * 
     * @return \TTI\CacheUtil\File
     */
    public function delete($key)
    {
        $regular = (string) preg_replace('/[^A-Z0-9\._-]/i', '', $key);
        $files = (array) glob($this->dir . 'cache.' . $regular . '.*');

        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
        return (object) $this;
    }
}
