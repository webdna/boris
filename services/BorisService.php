<?php
/**
 * Boris plugin for Craft CMS
 *
 * Boris Service
 *
 * @author    Nathaniel Hammond - @nfourtythree - webdna
 * @copyright Copyright (c) 2017 Nathaniel Hammond - @nfourtythree - webdna
 * @link      https://webdna.co.uk
 * @package   Boris
 * @since     1.0.0
 */

namespace Craft;

class BorisService extends BaseApplicationComponent
{

  private $elementIdHandles = array(
    'entryIds' => 'entries',
    'categoryIds' => 'categories',
  );

  /**
   * invincible - check to see if any of the IDs passed are invincible
   * @param  {array} $ids           array of IDs to check
   * @param  {array} $invincibleIds array of IDs that are invincible
   * @return {array}                array of invincible titles (indicating if there are any)
   */
  public function invincible( $ids, $invincibleIds )
  {

    $invincibleTitles = array();

    if ( $invincibleIds and !empty( $invincibleIds ) ) {
      foreach ( $ids as $id ) {

        if ( in_array( $id, $invincibleIds ) ) {
          $element = craft()->elements->getElementById( $id );
          if ( $element ) {
            $invincibleTitles[] = $element->title;
          }
        }

      }
    }

    return $invincibleTitles;
  }

  /**
   * showInvincibleNotice - show notice stating things haven't been deleted
   * @param  array  $titles
   */
  public function showInvincibleNotice( $titles = array() )
  {
    craft()->userSession->setNotice( Craft::t( 'The following elements are protected: {titles}', array( 'titles' => implode( ', ', $titles ) ) ) );
  }

  public function getTemplateVars( $settings )
  {
    $settings = $this->unprepSettings( $settings );

    $templateVars = array(
      'settings' => $settings,
      'entries' => array(),
      'categories' => array(),
    );

    foreach ( array_keys( $this->elementIdHandles ) as $elementIdHandle ) {

      if ( isset( $settings[ $elementIdHandle ] ) and $settings[ $elementIdHandle ] and !empty( $settings[ $elementIdHandle ] ) ) {
        $tmp = array();
        foreach ( $settings[ $elementIdHandle ] as $id ) {
          $tmpElement = craft()->elements->getElementById( $id );

          if ( $tmpElement ) {
            $tmp[] = $tmpElement;
          }
        }
        $templateVars[ $this->elementIdHandles[ $elementIdHandle ] ] = $tmp;
      }

    }

    return $templateVars;
  }

  public function prepSettings( $settings )
  {
    foreach ( array_keys( $this->elementIdHandles ) as $elementIdHandle ) {
      if ( isset( $settings[ $elementIdHandle ] ) ){
        $settings[ $elementIdHandle ] = serialize( $settings[ $elementIdHandle ] );
      }
    }

    return $settings;
  }

  public function unprepSettings( $settings )
  {
    foreach ( array_keys( $this->elementIdHandles ) as $elementIdHandle ) {
      if ( isset( $settings[ $elementIdHandle ] ) ) {
        $settings[ $elementIdHandle ] = unserialize( $settings[ $elementIdHandle ] );
      }
    }

    return $settings;
  }

}
