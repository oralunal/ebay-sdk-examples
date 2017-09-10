<?php
/**
 * Copyright 2017 Oral ÜNAL
 * https://twitter.com/oralunal
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * Include the SDK by using the autoloader from Composer.
 */
require __DIR__.'/../vendor/autoload.php';
/**
 * Include the configuration values.
 *
 * Ensure that you have edited the configuration.php file
 * to include your application keys.
 */
$config = require __DIR__.'/../configuration.php';
/**
 * The namespaces provided by the SDK.
 */
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;
/**
 * Create the service object.
 */
$service = new Services\TradingService([
    'credentials' => $config['sandbox']['credentials'],
    'siteId'      => Constants\SiteIds::US
]);
/**
 * Create the request object.
 */
$request = new Types\SetNotificationPreferencesRequestType();
/**
 * An user token is required when using the Trading service.
 */
$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
$request->RequesterCredentials->eBayAuthToken = $config['sandbox']['authToken'];

/**
 * Set the alarm.
 */
$request->ApplicationDeliveryPreferences = new Types\ApplicationDeliveryPreferencesType();
$request->ApplicationDeliveryPreferences->AlertEnable = Enums\EnableCodeType::C_ENABLE;
$request->ApplicationDeliveryPreferences->AlertEmail = "mailto://somename@somedomain.com";

/**
 * Set the URL that eBay will notify.
 */
$request->ApplicationDeliveryPreferences->ApplicationEnable = Enums\EnableCodeType::C_ENABLE;
$request->ApplicationDeliveryPreferences->ApplicationURL = "https://somedomain.com/someListeners/PHP_Soap_Handler.php";

/**
 * Set the Payload and Device type
 * Payload Version 991 is the latest
 */
$request->ApplicationDeliveryPreferences->DeviceType = Enums\DeviceTypeCodeType::C_PLATFORM;
$request->ApplicationDeliveryPreferences->PayloadVersion = "991";

/**
 * Register events we want
 * If you'd like to deregister events those you registered before, you should set event C_DISABLE
 */
$request->UserDeliveryPreferenceArray = new Types\NotificationEnableArrayType();
$udp = new Types\NotificationEnableType();
$udp->EventEnable = Enums\EnableCodeType::C_ENABLE;
$udp->EventType = Enums\NotificationEventTypeCodeType::C_FIXED_PRICE_TRANSACTION;
$request->UserDeliveryPreferenceArray->NotificationEnable[] = $udp;

$request->UserDeliveryPreferenceArray = new Types\NotificationEnableArrayType();
$udp = new Types\NotificationEnableType();
$udp->EventEnable = Enums\EnableCodeType::C_DISABLE;
$udp->EventType = Enums\NotificationEventTypeCodeType::C_ITEM_REVISED;
$request->UserDeliveryPreferenceArray->NotificationEnable[] = $udp;

/**
 * Send the request.
 */
$response = $service->setNotificationPreferences($request);
/**
 * Output the result of calling the service operation.
 */
if (isset($response->Errors)) {
    foreach ($response->Errors as $error) {
        printf(
            "%s: %s\n%s\n\n",
            $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
            $error->ShortMessage,
            $error->LongMessage
        );
    }
}
if ($response->Ack !== 'Failure') {
  // Do something
}
