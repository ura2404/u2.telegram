<?php

namespace Tg\Core;
use \Tg\Exception as ex;

/**
 * Class \Tg\Core\Webhook
 * 
 * @url https://core.telegram.org/bots/api#setwebhook
 */

class Webhook {
  public  string    $Url;                 // HTTPS URL to send updates to. Use an empty string to remove webhook integration
//public ?InputFile $Certificate;         // Optional Upload your public key certificate so that the root certificate in use can be checked. See our self-signed guide for details.
  public ?string    $IpAddress;           // Optional	The fixed IP address which will be used to send webhook requests instead of the IP address resolved through DNS
  public ?int       $MaxConnections;      // Optional	The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery, 1-100. Defaults to 40. 
                                          //    Use lower values to limit the load on your bot's server, and higher values to increase your bot's throughput.
  public ?array     $Allowedupdates;      // Optional	A JSON-serialized list of the update types you want your bot to receive. For example, specify ["message", "edited_channel_post", "callback_query"] 
                                          //    to only receive updates of these types. See Update for a complete list of available update types. Specify an empty list to receive all update types except chat_member, 
                                          //    message_reaction, and message_reaction_count (default). If not specified, the previous setting will be used.
  public ?bool      $DropPendingUpdates;  // Optional	Pass True to drop all pending updates
  public ?string    $SecretToken;         // Optional	A secret token to be sent in a header “X-Telegram-Bot-Api-Secret-Token” in every webhook request, 1-256 characters. Only characters A-Z, a-z, 0-9, _ and - are allowed. 
                                          //    The header is useful to ensure that the request comes from a webhook set by you.

  // --------------------------------------------------------------------------
  function __construct( array $Data ){
    $this->Url                = $Data['url'];
 // $this->Certificate        = isset( $Data['certificate'] )          ? $Data['certificate']          : null;
    $this->IpAddress          = isset( $Data['ip_address'] )           ? $Data['ip_address']           : null;
    $this->MaxConnections     = isset( $Data['max_connections'] )      ? $Data['max_connections']      : null;
    $this->Allowedupdates     = isset( $Data['allowed_updates'] )      ? $Data['allowed_updates']      : null;
    $this->DropPendingUpdates = isset( $Data['drop_pending_updates'] ) ? $Data['drop_pending_updates'] : null;
    $this->SecretToken        = isset( $Data['secret_token'] )         ? $Data['secret_token']         : null;
  }

  // --------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  /**
   * Function info()
   * 
   * @url https://core.telegram.org/bots/api#getwebhookinfo
   * Describes the current status of a webhook.
   * 
   * @return 
   * - url                              String                    Webhook URL, may be empty if webhook is not set up
   * - has_custom_certificate           Boolean                   True, if a custom certificate was provided for webhook certificate checks
   * - pending_update_count             Integer                   Number of updates awaiting delivery
   * - ip_address                       String          Optional  Currently used webhook IP address
   * - last_error_date                  Integer         Optional  Unix time for the most recent error that happened when trying to deliver an update via webhook
   * - last_error_message               String          Optional  Error message in human-readable format for the most recent error that happened when trying to deliver an update via webhook
   * - last_synchronization_error_date  Integer         Optional  Unix time of the most recent error that happened when trying to synchronize available updates with Telegram datacenters
   * - max_connections                  Integer         Optional  The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery
   * - allowed_updates                  Array of String Optional  A list of update types the bot is subscribed to. Defaults to all update types except chat_member
   */

  static function info(): array {
    // 1. получить ответ
    $Responce = \Tg\Connect::instance()->post( 'getWebhookInfo' );

    // 2. перевести дату в понятный вид 
    isset( $Responce[ 'result' ][ 'last_error_date' ] ) ? $Responce[ 'result' ][ 'last_error_date' ] = gmdate( "Y-m-d H:i:s", $Responce[ 'result' ][ 'last_error_date' ] ) : null;

    // 3. вернуть ответ
    return $Responce;
  }

  // --------------------------------------------------------------------------
  /**
   * @url https://core.telegram.org/bots/api#setwebhook
   * 
   * @param array $Params
   * - url                  String          Yes       HTTPS URL to send updates to. Use an empty string to remove webhook integration
   * - certificate          InputFile       Optional  Upload your public key certificate so that the root certificate in use can be checked. See our self-signed guide for details.
   * - ip_address           String          Optional  The fixed IP address which will be used to send webhook requests instead of the IP address resolved through DNS
   * - max_connections      Integer         Optional  The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery, 1-100. Defaults to 40. Use lower values to limit the load on your bot's server, 
   *                                                  and higher values to increase your bot's throughput.
   * - allowed_updates      Array of String Optional  A JSON-serialized list of the update types you want your bot to receive. For example, specify ["message", "edited_channel_post", "callback_query"] 
   *                                                  to only receive updates of these types. See Update for a complete list of available update types. Specify an empty list to receive all update types except chat_member, 
   *                                                  message_reaction, and message_reaction_count (default). If not specified, the previous setting will be used.
   * - drop_pending_updates Boolean         Optional  Pass True to drop all pending updates
   * - secret_token         String          Optional  A secret token to be sent in a header “X-Telegram-Bot-Api-Secret-Token” in every webhook request, 1-256 characters. Only characters A-Z, a-z, 0-9, _ and - are allowed. 
   *                                                  The header is useful to ensure that the request comes from a webhook set by you.
   */
  static function set(): array {
    // 1. получить instance конфига
    $Config = \Tg\Config::instance();

    // 2. сгенерировать секретный токен
    $s1 = md5(rand(1,999999).time().microtime());
    $s2 = md5(rand(1,999999).time().microtime());
    $SecretToken = $s1 . '-' . $s2;

    // 3. установить webhook
    $Responce = \Tg\Connect::instance()->post( 'setWebhook', [
      'url'          => $Config->getValue('webhookUrl'),
      'secret_token' => $SecretToken,
    ]);

    // 4. сохранить секретный токен
    $Config->setValue( 'secretToken', $SecretToken );

    // 5. вернуть ответ
    return $Responce;
  }  

  // --------------------------------------------------------------------------
  /**
   * @url https://core.telegram.org/bots/api#deletewebhook
   * 
   * @param array $Params
   *  - drop_pending_updates  Boolean Optional  Pass True to drop all pending updates
   */
  static function delete(): array {
    // 1. получить instance конфига
    $Config = \Tg\Config::instance();

    // 2. удалить webhook
    $Responce = \Tg\Connect::instance()->post( 'deleteWebhook', null );

    // 3. удалить секретный токен
    $Config->delValue( 'secretToken' );

    // 5. вернуть ответ
    return $Responce;
  }

  // --------------------------------------------------------------------------
  /**
   *  Проверка secretToken
   */
  static function check(): bool {
    $Config = \Tg\Config::instance();
    $SecretToken = isset( $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ) ? $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] : null;
    return $Config->isEnable( 'secretToken' ) && $Config->getValue( 'secretToken' ) == $SecretToken;
  }
}
?>