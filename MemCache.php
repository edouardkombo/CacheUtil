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
 * Memcache driver responsibility is to handle cache via MemCache.
 *
 * @category Cache
 * @package  CacheUtil
 * @author   Edouard Kombo <edouard.kombo@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://www.breezeframework.com/thetrollinception.php
 */
class MemCache extends HandleAbstraction
{
    /**
     *
     * @var int $expire Time for expiration
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
     * 
     * @param string $cache_hostname Hostname 
     * @param int    $cache_port     Port
     */
    public function __construct($cache_hostname, $cache_port)
    {
        $this->cache = new Memcache();
        $this->cache->pconnect($cache_hostname, $cache_port);        
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
     * @param int $time time for expiration
     * 
     * @return \TTI\CacheUtil\MemCache
     */    
    protected function expire($time)
    {
        $this->expire = ($time === null) ? $this->expire : $time ;
        return (object) $this;
    }

    /**
     * Set the prefix of cache name
     *
     * @param string $prefix Name for cache
     * 
     * @return \TTI\CacheUtil\MemCache
     */
    protected function prefix($prefix)
    {
        $this->prefix = ($prefix === null) ? '' : $prefix ;
        return (object) $this;
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
        return $this->cache->get($this->prefix . $key);
    }

    /**
     * Set a value in the cache
     *
     * @param string $key   key to assign
     * @param mixed  $value We assign whatever we want to key
     * 
     * @return \TTI\CacheUtil\MemCache
     */
    public function set($key, $value)
    {
        $mc = MEMCACHE_COMPRESSED;
        $this->cache->set($this->prefix . $key, $value, $mc, $this->expire);
        return (object) $this;
    }

    /**
     * Delete an entry from cache
     * 
     * @param string $key Key to delete from cache
     * 
     * @return \TTI\CacheUtil\MemCache
     */
    public function delete($key)
    {
        $this->cache->delete($this->prefix . $key);
        return (object) $this;
    }
}
