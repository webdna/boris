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
  /**
   * invincible - check to see if any of the IDs passed are invincible
   * @param  {array} $ids           array of IDs to check
   * @param  {array} $invincibleIds array of IDs that are invincible
   * @return {array}                array of invincible titles (indicating if there are any)
   */
  public function invincible( $ids, $invincibleIds )
  {

    $invincibleTitles = array();

    foreach ( $ids as $id ) {

      if ( in_array( $id, $invincibleIds ) ) {
        $element = craft()->elements->getElementById( $id );
        if ( $element ) {
          $invincibleTitles[] = $element->title;
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

}
