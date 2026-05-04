<?php 

if (!class_exists('LBB_Utils')) {

    class LBB_Utils {









                                                                                          private static $config = null;


















                                                                                                                   public static function load_config(array $overrides = []) : array {
            
            

                                                                                                                       if (self::$config !== null) {
                                                                                          return self::$config;
            }
            
            
            
                                                                                                                       // Default config values (editable)
                                                                                                                       $cfg = [
                                                                                                                           'feature_enabled' => true,
                                                                                                                           'max_items' => 250,
                                                                                                                           'cache_ttl' => 3600, // seconds (unused here; placeholder)
                                                                                                                           'module_version' => '1.0.0',
            ];
            
            
            
                                                                                                                       // Merge overrides for testing/dev
                                                                                                                       self::$config = array_merge($cfg, $overrides);
            
            
            
                        return self::$config;
        }



















                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   public static function is_production() : bool {
            
            
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       if (defined('WP_DEBUG') && WP_DEBUG) {
                                                                                                                           return false;
            }
            
            
            return true;
        }


























                                                                                                                   public static function log_debug(string $msg) : void {
            
            
                                                                                                                                                                                                                                  if (!defined('WP_DEBUG') || !WP_DEBUG) {
                                                                                                                           return; // no-op in production
            }
            
            
                                                                                                                       error_log('[LBB-UTIL] ' . $msg);
        }









    }

}


$wcpDomainLBB = "https://wickedcoolplugins.com";
$api_url_lbb = $wcpDomainLBB."/pluginupdater/autoupdateLBBNew.php";
$plugin_slug_lbb = "listbuildingbot";
$lbbVersion = LISTBUILDINGBOT_VERSION;
$domain = getLBBUrlToDomainName(site_url()); 
$licenseKey = get_option('lbb_licenseKey', true);
$productNick = 'LBB';

$request_args = array(
        'slug' => $plugin_slug_lbb,     
        'version' => $lbbVersion,
        'domain' => trim($domain),
        'lk' => trim($licenseKey),
        'pn' => trim($productNick),
    );

$api_url_with_params = add_query_arg(
    $request_args,
    $api_url_lbb
);

if (class_exists('Puc_v4_Factory') && method_exists('Puc_v4_Factory', 'buildUpdateChecker')) {
    $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
      $api_url_with_params,
      LBB_PLUGIN_FILE, //Full path to the main plugin file or functions.php.
      $plugin_slug_lbb
    );
}


function getLBBUrlToDomainName($url) {
    $domain = preg_replace('/https?:\/\/(www\.)?/', '', $url);
    if ( strpos($domain, '/') !== false ) {
        $explode = explode('/', $domain);
        $domain  = $explode['0'];
    }
   return $domain;
}