<?php

namespace Tg\Core;
use \Tg\Exception as ex;

/**
 * Class \Tg\Core\Bot
 * 
 * @url https://core.telegram.org/bots/api#getwebhookinfo -> \U2\Tg\Webhook->info
 * @url https://core.telegram.org/bots/api#setwebhook     -> \U2\Tg\Webhook->set
 * @url https://core.telegram.org/bots/api#deletewebhook  -> \U2\Tg\Webhook->delete
 */

class Bot {
    
  // --------------------------------------------------------------------------
  function __construct(){
  }

  // --------------------------------------------------------------------------
  function __get($name){
    switch($name){
      default : throw new ex\Property($this, $name);
    }
  }
  
  // --------------------------------------------------------------------------
  /**
   * @url https://core.telegram.org/bots/api#sendmessage
   * 
   * @param array $Params
   * - business_connection_id String                  Optional  Unique identifier of the business connection on behalf of which the message will be sent
   * - chat_id                Integer or String       Yes       Unique identifier for the target chat or username of the target channel (in the format @channelusername)
   * - message_thread_id      Integer                 Optional  Unique identifier for the target message thread (topic) of the forum; for forum supergroups only
   * - text                   String                  Yes       Text of the message to be sent, 1-4096 characters after entities parsing
   * - parse_mode             String                  Optional  Mode for parsing entities in the message text. See formatting options for more details.
   * - entities               Array of MessageEntity	Optional  A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
   * - link_preview_options	  LinkPreviewOptions      Optional  Link preview generation options for the message
   * - disable_notification   Boolean                 Optional  Sends the message silently. Users will receive a notification with no sound.
   * - protect_content        Boolean                 Optional  Protects the contents of the sent message from forwarding and saving
   * - message_effect_id      String                  Optional  Unique identifier of the message effect to be added to the message; for private chats only
   * - reply_parameters       ReplyParameters	        Optional  Description of the message to reply to
   * - reply_markup	          InlineKeyboardMarkup or 
   *                          ReplyKeyboardMarkup or 
   *                          ReplyKeyboardRemove or 
   *                          ForceReply              Optional  Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user
   */
  public function sendMessage( array $Params ): array {
    return \Tg\Connect::i()->post( 'sendMessage', $Params );
  }

  // --------------------------------------------------------------------------
  /**
   * @url https://core.telegram.org/bots/api#deletemessage
   * 
   * @param array $Params
   * - chat_id      Integer or String   Yes   Unique identifier for the target chat or username of the target channel (in the format @channelusername)
   * - message_id   Integer             Yes   Identifier of the message to delete
   */
  public function deleteMessage( array $Params ): array {
    return \Tg\Connect::i()->post( 'deleteMessage', $Params );
  }


  // --------------------------------------------------------------------------
  /**
   * @url https://core.telegram.org/bots/api#editmessagetext
   * 
   * @param array $Params
   * - business_connection_id	String	                Optional  Unique identifier of the business connection on behalf of which the message to be edited was sent
   * - chat_id	              Integer or String	      Optional  Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target channel (in the format @channelusername)
   * - message_id	            Integer                 Optional  Required if inline_message_id is not specified. Identifier of the message to edit
   * - inline_message_id  	  String	                Optional  Required if chat_id and message_id are not specified. Identifier of the inline message
   * - text	                  String      	          Yes       New text of the message, 1-4096 characters after entities parsing
   * - parse_mode	            String                  Optional  Mode for parsing entities in the message text. See formatting options for more details.
   * - entities	              Array of MessageEntity  Optional  A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
   * - link_preview_options  	LinkPreviewOptions      Optional  Link preview generation options for the message
   * - reply_markup	          InlineKeyboardMarkup    Optional  A JSON-serialized object for an inline keyboard.
   */
  public function editMessageText( array $Params ): array {
    return \Tg\Connect::i()->post( 'editMessageText', $Params );
  }

  // --------------------------------------------------------------------------
  public function getUpdate(): array {
    $Responce = file_get_contents('php://input');

    $Update = json_decode( $Responce, JSON_OBJECT_AS_ARRAY ); 
    $Update = new Update($Update); 

    return [ $Update ];
  }
  
  // --------------------------------------------------------------------------
  /**
   * @url https://core.telegram.org/bots/api#getupdates
   * Use this method to receive incoming updates using long polling (wiki). Returns an Array of Update objects.
   * 
   * @param array $Params
   * - offset           Integer           Optional  Identifier of the first update to be returned. Must be greater by one than the highest among the identifiers of previously received updates. By default, updates starting with the earliest unconfirmed update are returned. An update is considered confirmed as soon as getUpdates is called with an offset higher than its update_id. The negative offset can be specified to retrieve updates starting from -offset update from the end of the updates queue. All previous updates will be forgotten.
   * - limit            Integer           Optional  Limits the number of updates to be retrieved. Values between 1-100 are accepted. Defaults to 100.
   * - timeout          Integer           Optional  Timeout in seconds for long polling. Defaults to 0, i.e. usual short polling. Should be positive, short polling should be used for testing purposes only.
   * - allowed_updates  Array of String   Optional  A JSON-serialized list of the update types you want your bot to receive. For example, specify ["message", "edited_channel_post", "callback_query"] to only receive updates of these types. See Update for a complete list of available update types. Specify an empty list to receive all update types except chat_member, message_reaction, and message_reaction_count (default). If not specified, the previous setting will be used.
   */
  public function getUpdates( array $Params=null): array {
    $Response = \Tg\Telegram::$CONNECT->post( 'getUpdates', [
      'offset' => $this->Config->LastUpdateId
    ] );

    //_log( ROOT.'/log/123', $Response );
    return [];

    if ( $Response['ok'] && isset($Response['result']) ) {
      foreach ($Response['result'] as $Key=>$Update ){
        $Response['result'][$Key] = new Update($Update);
      }
    }

    // 'offset' => $LastupdateId,
    // 'limit'  => 100

    if ( count($Response['result']) > 0 ) {
      $LastUpdateId = $Response['result'][ count($Response['result'])-1 ];
      $this->Config->setValue( 'lastUpdateId', $LastUpdateId->Id + 1 );
    }

    return $Response['result'];    
  }
}
?>
