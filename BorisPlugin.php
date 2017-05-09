<?php
/**
 * Boris plugin for Craft CMS
 *
 * Make your entries invincible!
 *
 * @author    Nathaniel Hammond - @nfourtythree - webdna
 * @copyright Copyright (c) 2017 Nathaniel Hammond - @nfourtythree - webdna
 * @link      https://webdna.co.uk
 * @package   Boris
 * @since     1.0.0
 */

namespace Craft;

class BorisPlugin extends BasePlugin
{
    /**
     * Called after the plugin class is instantiated; do any one-time initialization here such as hooks and events:
     *
     * craft()->on('entries.saveEntry', function(Event $event) {
     *    // ...
     * });
     *
     * or loading any third party Composer packages via:
     *
     * require_once __DIR__ . '/vendor/autoload.php';
     *
     * @return mixed
     */
    public function init()
    {
      parent::init();

      craft()->on( 'entries.onBeforeDeleteEntry', function( Event $event ) {

        $settings = $this->getSettings();
        $settings[ 'elementIds' ] = unserialize( $settings[ 'elementIds' ] );

        if ( $event->params[ 'entry' ] and ( $settings[ 'elementIds' ] ) and count( $settings[ 'elementIds' ] ) ) {

          $titles = craft()->boris->invincible( array( $event->params[ 'entry' ]->id ), $settings[ 'elementIds' ] );

          if ( count( $titles ) ) {
            craft()->boris->showInvincibleNotice( $titles );
            $event->performAction = false;
          }

        }

      } );

      craft()->on( 'elements.onBeforePerformAction', function( Event $event ) {

        $settings = $this->getSettings();
        $settings[ 'elementIds' ] = unserialize( $settings[ 'elementIds' ] );

        if ( $event->params[ 'action' ]->classHandle == 'Delete' ) {

          $titles = craft()->boris->invincible( $event->params[ 'criteria' ]->ids(), $settings[ 'elementIds' ] );

          if ( count( $titles ) ) {
            // TODO: Figure out how to pass back notice on performAction
            // craft()->boris->showInvincibleNotice( $titles );
            $event->performAction = false;
          }
        }

      } );
    }

    /**
     * Returns the user-facing name.
     *
     * @return mixed
     */
    public function getName()
    {
      $settings = $this->getSettings();

      if ( $settings->name ) {
        return $settings->name;
      }

      return Craft::t( 'Boris' );
    }

    /**
     * Plugins can have descriptions of themselves displayed on the Plugins page by adding a getDescription() method
     * on the primary plugin class:
     *
     * @return mixed
     */
    public function getDescription()
    {
      return Craft::t( 'Make your entries invincible!' );
    }

    /**
     * Plugins can have links to their documentation on the Plugins page by adding a getDocumentationUrl() method on
     * the primary plugin class:
     *
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://github.com/webdna/boris/blob/master/README.md';
    }

    /**
     * Plugins can now take part in Craft’s update notifications, and display release notes on the Updates page, by
     * providing a JSON feed that describes new releases, and adding a getReleaseFeedUrl() method on the primary
     * plugin class.
     *
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/webdna/boris/master/releases.json';
    }

    /**
     * Returns the version number.
     *
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * As of Craft 2.5, Craft no longer takes the whole site down every time a plugin’s version number changes, in
     * case there are any new migrations that need to be run. Instead plugins must explicitly tell Craft that they
     * have new migrations by returning a new (higher) schema version number with a getSchemaVersion() method on
     * their primary plugin class:
     *
     * @return string
     */
    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    /**
     * Returns the developer’s name.
     *
     * @return string
     */
    public function getDeveloper()
    {
        return 'Nathaniel Hammond - @nfourtythree - webdna';
    }

    /**
     * Returns the developer’s website URL.
     *
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'https://webdna.co.uk';
    }

    /**
     * Returns whether the plugin should get its own tab in the CP header.
     *
     * @return bool
     */
    public function hasCpSection()
    {
        return false;
    }

    /**
     * Called right before your plugin’s row gets stored in the plugins database table, and tables have been created
     * for it based on its records.
     */
    public function onBeforeInstall()
    {
    }

    /**
     * Called right after your plugin’s row has been stored in the plugins database table, and tables have been
     * created for it based on its records.
     */
    public function onAfterInstall()
    {
    }

    /**
     * Called right before your plugin’s record-based tables have been deleted, and its row in the plugins table
     * has been deleted.
     */
    public function onBeforeUninstall()
    {
    }

    /**
     * Called right after your plugin’s record-based tables have been deleted, and its row in the plugins table
     * has been deleted.
     */
    public function onAfterUninstall()
    {
    }

    /**
     * Defines the attributes that model your plugin’s available settings.
     *
     * @return array
     */
    protected function defineSettings()
    {
      return array(
        'name' => array( AttributeType::String, 'label' => 'Plugin Name', 'default' => 'Boris' ),
        'elementIds' => array( AttributeType::Mixed, 'label' => 'Invincible Elemtns', 'default' => serialize( array() ) ),
      );
    }

    /**
     * Returns the HTML that displays your plugin’s settings.
     *
     * @return mixed
     */
    public function getSettingsHtml()
    {

//       $fieldType = $field->fieldType;
//       $settings = $fieldType->getSettings();
//       $criteria = null;
//
//       if ( !( $criteria instanceof ElementCriteriaModel ) )
// {
//     $criteria = craft()->elements->getCriteria( $fieldType->elementType->getClassHandle() );
//     $criteria->id = false;
// }
//
//        $criteria->status = null;
//        $criteria->localeEnabled = null;
//
//        $selectionCriteria = array();
//        $selectionCriteria['localeEnabled'] = null;
//
//        if ( craft()->isLocalized() ) {
//          $targetLocale = $settings->targetLocale;
//        } else {
//          $targetLocale = craft()->getLanguage();
//        }
//        $selectionCriteria['locale'] = $targetLocale;
//
//        return array(
//             'jsClass'            => $this->getInputJsClass( $fieldType->elementType->classHandle ),
//             'elementType'        => new ElementTypeVariable( $fieldType->elementType ),
//             'id'                 => craft()->templates->formatInputId( $field->handle ),
//             'fieldId'            => $fieldType->model->id,
//             'storageKey'         => 'field.' . $fieldType->model->id,
//             'name'               => $field->handle,
//             'elements'           => $criteria,
//             'sources'            => ( $this->getAllowMultipleSources( $fieldType->classHandle ) ) ? $settings->sources : array( $settings->source ),
//             'criteria'           => $selectionCriteria,
//             'sourceElementId'    => ( isset($fieldType->element->id ) ? $fieldType->element->id : null),
//             'limit'              => null,
//             'viewMode'           => $settings->viewMode,
//             'selectionLabel'     => ($settings->selectionLabel ? Craft::t($settings->selectionLabel) : Craft::t('Add {type}', array(
//                   'type' => StringHelper::toLowerCase( $fieldType->elementType->classHandle )
//             ) ) )
//        );


      $settings = $this->getSettings();
      $settings[ 'elementIds' ] = unserialize( $settings[ 'elementIds' ] );

      $elements = array();
      if ( $settings[ 'elementIds'] and count( $settings[ 'elementIds' ] ) ) {
        foreach ( $settings[ 'elementIds' ] as $elementId ) {
          $element = craft()->elements->getElementById( $elementId );
          if ( $element ) {
            $elements[] = $element;
          }
        }
      }

       return craft()->templates->render( 'boris/settings', array(
           'settings' => $this->getSettings(),
           'elements' => $elements,
       ) );
    }

    /**
     * If you need to do any processing on your settings’ post data before they’re saved to the database, you can
     * do it with the prepSettings() method:
     *
     * @param mixed $settings  The Widget's settings
     *
     * @return mixed
     */
    public function prepSettings( $settings )
    {
      // Modify $settings here...
      $settings[ 'elementIds' ] = serialize( $settings[ 'elementIds' ] );

      return $settings;
    }

}
