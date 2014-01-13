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
 * Apc driver responsibility is to handle cache via APC.
 *
 * @category Cache
 * @package  CacheUtil
 * @author   Edouard Kombo <edouard.kombo@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://www.breezeframework.com/thetrollinception.php
 */
class Apc extends HandleAbstraction
{
    /**
     *
     * @var integer $expire Time for expiration
     */
    protected $expire = 3600;
    
    /**
     *
     * @var mixed $cache Cache container
     */
    protected $cache;
    
    /**
     *
     * @var string $prefix Prefix to use for cache
     */
    protected $prefix;    
    
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
     * Expiration time
     *
     * @param integer $time time for expiration
     * 
     * @return \TTI\CacheUtil\Apc
     */    
    protected function expire($time)
    {
        $this->expire = ($time === null) ? $this->expire : $time ;
        return $this;
    }

    /**
     * Set the prefix of cache name
     *
     * @param string $prefix Name for cache
     * 
     * @return string
     */
    protected function prefix($prefix)
    {
        return $this->prefix = ($prefix === null) ? '' : $prefix ;
    }    
    
    /**
     * Get the final value
     *
     * @param string $key key to retrieve
     * 
     * @return mixed
     */
    public function get($key)
    {
        return apc_fetch($this->prefix . $key);
    }

    /**
     * Set a value in the cache
     *
     * @param string $key   key to assign
     * @param mixed  $value We assign whatever we want to key
     * 
     * @return \TTI\CacheUtil\Apc
     */
    public function set($key, $value)
    {
        $this->cache = apc_store($this->prefix . $key, $value, $this->expire);
        return (object) $this;
    }

    /**
     * Delete an entry from cache
     * 
     * @param string $key Key to delete from cache
     * 
     * @return \TTI\CacheUtil\Apc
     */
    public function delete($key)
    {
        $this->cache = apc_delete($this->prefix . $key);
        return (object) $this;
    }
}
