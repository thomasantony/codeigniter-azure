<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * A Windows Azure Table Storage driver that implements APC ( or other ) query caching 
 *
 * @author Thomas Antony
 * @website http://www.thomasantony.net
 *
 */
class Microsoft_WindowsAzure_Storage_Table_Cached
{
    public function __construct($host = Microsoft_WindowsAzure_Storage::URL_DEV_TABLE, $accountName = Microsoft_WindowsAzure_Credentials_CredentialsAbstract::DEVSTORE_ACCOUNT, $accountKey = Microsoft_WindowsAzure_Credentials_CredentialsAbstract::DEVSTORE_KEY, $usePathStyleUri = false, Microsoft_WindowsAzure_RetryPolicy_RetryPolicyAbstract $retryPolicy = null)
    {
        require_once ('Microsoft/AutoLoader.php');
        $this->client = new Microsoft_WindowsAzure_Storage_Table($host, $accountName, $accountKey, $usePathStyleUri, $retryPolicy);
        $this->CI =& get_instance();
        $this->CI->load->driver('cache', array(
            'adapter' => $this->CI->config->item('azure_caching_adapter'), 
            'backup' => $this->CI->config->item('azure_caching_adapter')
            )
        );
        $this->cache = $this->CI->cache;
        
    }
    public function clear_cache($table_name = null)
    {
        if(!$table_name)
        {
            $this->cache->clean();
        }else{
            $method_cached_list = $this->cache->get('table_'.$table_name);
            if($method_cached_list)
            {
                foreach($method_cached_list as $key)
                {
                    $this->cache->delete($key);
                }
                $this->cache->delete('table_'.$table_name);
            }
        }
    }
    public function __call($method, $args)
    {
        $cached_methods = array('retrieveEntityById','retrieveEntities');
        $cache_clear_methods = array('insertEntity','deleteEntity');
        
        if(in_array($method,$cached_methods))
        {
            $key = $method.'_'.md5(json_encode($args));
            if ( ! $result = $this->cache->get($key))
            {
                 // Also save the list of method calls to this table (arg[0]) in separate entity
                $method_cache_list = $this->cache->get('table_'.$args[0]);
                if(!$method_cache_list)
                {
                    $method_cache_list  = array($key);
                } else {
                    $method_cache_list[]= $key;
                }
                $this->cache->save('table_'.$args[0], $method_cache_list);
                
                // If no result in cache, make azure query and cache it
                $result = call_user_func_array(array($this->client,$method), $args);
                $this->cache->save($key, $result, $this->CI->config->item('azure_caching_interval'));
            }
            return $result;
        }else if(in_array($method,$cache_clear_methods))
        {
            // If method is one which causes data to change, delete all cache entries
            // for that particular table.
            $this->clear_cache($args[0]);
        }
        return call_user_func_array(array($this->client,$method), $args);
    }
}
?>