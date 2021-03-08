<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class ht_google_local_business
  {
    public $code;
    public $group;
    public string $title;
    public string $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;

    public function __construct()
    {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_header_tags_google_local_business_title');
      $this->description = CLICSHOPPING::getDef('module_header_tags_google_local_business_description');

      if (\defined('MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_STATUS')) {
        $this->sort_order = MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_STATUS == 'True');
      }
    }


//https://www.searchcommander.com/seo-tools/structured-data-builder/
//https://search.google.com/structured-data/testing-tool

    public function execute()
    {
      $CLICSHOPPING_Template = Registry::get('Template');

      $output = '<!--  Google local business -->' . "\n";
      $output .= '
  <script type="application/ld+json">
		{
    "@context" : "http://schema.org",
    "@type" : "LocalBusiness",
    "@id": "' . CLICSHOPPING::getConfig('http_server', 'Shop') . '",
    "image" : "' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_LOGO . '",
    "name" : "' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_NAME . '",
    "telephone" : "' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_TELEPHONE . '",
    "address": {
      "@type": "PostalAddress",
               "streetAddress" : "' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_ADDRESS . '",
               "addressLocality" : "' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_CITY . '",
                "addressRegion" : "' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_COUNTRY . '",
                "postalCode" : "' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_POSTALCODE . '"
                },
    "description" : "' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_DESCRIPTION . '",
    "url" : "' . CLICSHOPPING::getConfig('http_server', 'Shop') . '",
    "logo" : "' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_LOGO . '",
';

      if (!empty(MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_LATITUDE) && !empty(MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_LONGITUDE)) {
        $output .= '
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": ' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_LATITUDE . ',
      "longitude": ' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_LONGITUDE . '
    },
    ';
      }

      $output .= '
     "sameAs": [
     ';

      if (!empty(MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_FACEBOOK)) {
        $output .= '"' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_FACEBOOK . '", ';
      }
      if (!empty(MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_TWITTER)) {
        $output .= '"' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_TWITTER . '", ';
      }

      if (!empty(MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_TWITTER)) {
        $output .= '"' . MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_INSTAGRAM . '" ';
      }
      $output .= ' ]';
      $output .= ' }	</script>' . "\n";

      $output .= '<!--  Google local business -->' . "\n";

      $CLICSHOPPING_Template->addBlock($output, 'footer_scripts');
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function check()
    {
      return \defined('MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_STATUS');
    }


    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to install this module ?',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Envoyer des informations à Google concernant votre entreprise',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer l\'url de l\'image de votre logo',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_NAME',
          'configuration_value' => '',
          'configuration_description' => 'Nom de votre entreprise',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer la description d\'activité de votre société',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_DESCRIPTION',
          'configuration_value' => '',
          'configuration_description' => 'Description de l\'activité de votre société',
          'configuration_group_id' => '6',
          'sort_order' => '3',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer l\'url de l\'image de votre logo',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_LOGO',
          'configuration_value' => '',
          'configuration_description' => 'Url du logo du site',
          'configuration_group_id' => '6',
          'sort_order' => '4',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer votre numéro de téléphone',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_TELEPHONE',
          'configuration_value' => '',
          'configuration_description' => 'Téléphone de votre entreprise',
          'configuration_group_id' => '6',
          'sort_order' => '5',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer l\'adresse de votre entreprise',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_ADDRESS',
          'configuration_value' => '',
          'configuration_description' => 'Adresse de votre entreprise',
          'configuration_group_id' => '6',
          'sort_order' => '12',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer la ville de votre entreprise',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_CITY',
          'configuration_value' => '',
          'configuration_description' => 'Ville de votre entreprise',
          'configuration_group_id' => '6',
          'sort_order' => '13',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer le pays de votre entreprise',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_COUNTRY',
          'configuration_value' => '',
          'configuration_description' => 'Pays de votre entreprise',
          'configuration_group_id' => '6',
          'sort_order' => '14',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer le code postal de votre entreprise',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_POSTALCODE',
          'configuration_value' => '',
          'configuration_description' => 'Code postal de votre entreprise',
          'configuration_group_id' => '6',
          'sort_order' => '14',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer la latitude de la localisation de votre entreprise',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_LATITUDE',
          'configuration_value' => '',
          'configuration_description' => 'latitude de la localisation de votre entreprise (ex: 37.293058)',
          'configuration_group_id' => '6',
          'sort_order' => '12',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer la latitude de la localisation de votre entreprise',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_LONGITUDE',
          'configuration_value' => '',
          'configuration_description' => 'latitude de la localisation de votre entreprise (ex:-121.988331)',
          'configuration_group_id' => '6',
          'sort_order' => '13',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer L\'url de votre compte Facebook',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_FACEBOOK',
          'configuration_value' => '',
          'configuration_description' => 'url de votre compte Facebook',
          'configuration_group_id' => '6',
          'sort_order' => '15',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer L\'url de votre compte Twitter',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_TWITTER',
          'configuration_value' => '',
          'configuration_description' => 'url de votre compte Twitter',
          'configuration_group_id' => '6',
          'sort_order' => '17',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Veuillez indiquer L\'url de votre compte Instagram',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_INSTAGRAM',
          'configuration_value' => '',
          'configuration_description' => 'url de votre compte Instagram',
          'configuration_group_id' => '6',
          'sort_order' => '17',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort Order',
          'configuration_key' => 'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_SORT_ORDER',
          'configuration_value' => '45',
          'configuration_description' => 'Sort order. Lowest is displayed in first',
          'configuration_group_id' => '6',
          'sort_order' => '15',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove()
    {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys()
    {
      return array('MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_STATUS',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_NAME',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_DESCRIPTION',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_LOGO',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_TELEPHONE',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_ADDRESS',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_CITY',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_COUNTRY',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_POSTALCODE',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_LATITUDE',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_LONGITUDE',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_FACEBOOK',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_TWITTER',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_URL_INSTAGRAM',
        'MODULE_HEADER_GOOGLE_LOCAL_BUSINESS_SORT_ORDER'
      );
    }
  }
