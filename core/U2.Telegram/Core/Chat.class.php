<?php

namespace Tg\Core;
use \Tg\Exception as ex;

/**
 * Class \Tg\Core\Chat
 * 
 * @url https://core.telegram.org/bots/api#chat
 * 
 * @properties
 * - id           Integer               Unique identifier for this chat. This number may have more than 32 significant bits and some programming languages 
 *                                          may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or 
 *                                          double-precision float type are safe for storing this identifier.
 * - type         String                Type of the chat, can be either “private”, “group”, “supergroup” or “channel”
 * - title        String    Optional    Title, for supergroups, channels and group chats
 * - username     String    Optional    Username, for private chats, supergroups and channels if available
 * - first_name   String    Optional    First name of the other party in a private chat
 * - last_name	  String    Optional    Last name of the other party in a private chat
 * - is_forum     Boolean   Optional    True, if the supergroup chat is a forum (has topics enabled)
 */

class Chat {
  public  int    $Id;
  public  string $Type;
  public ?string $Title;
  public ?bool   $IsForum;
  public ?USer   $User;
    
  // --------------------------------------------------------------------------
  function __construct(array $Chat){
    $this->Id      = $Chat['id'];
    $this->Type    = $Chat['type'];
    $this->Title   = isset($Chat['title'])    ? $Chat['title']    : null;
    $this->IsForum = isset($Chat['is_forum']) ? $Chat['is_forum'] : null;
    $this->User    = new User($Chat);
  }

  // --------------------------------------------------------------------------
  function __get($name){
    switch($name){
      default : throw new ex\Property($this, $name);
    }
  }

}
?>
