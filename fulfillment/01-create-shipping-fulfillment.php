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
use \DTS\eBaySDK\Fulfillment\Services;
use \DTS\eBaySDK\Fulfillment\Types;
use \DTS\eBaySDK\Fulfillment\Enums;

/**
 * Create the service object.
 */
$service = new Services\FulfillmentService([
    'authorization' => $config['production']['oauthUserToken']
    'siteId' => \DTS\eBaySDK\Constants\SiteIds::US
]);

/**
 * Create the request object.
 */
$request = new Types\CreateAShippingFulfillmentRestRequest();


$request->orderId = "21312";

$lineItems = new Types\LineItemReference();
$lineItems->lineItemId = "1212";
$lineItems->quantity = 1;
$request->lineItems[] = $lineItems;

$lineItems = new Types\LineItemReference();
$lineItems->lineItemId = "21323";
$lineItems->quantity = 2;
$request->lineItems[] = $lineItems;

$request->shippedDate = "Date";
$request->shippingCarrierCode = 'UPS';
$request->trackingNumber = "PZ9894385943";

/**
 * Send the request
 */
$response = $service->createAShippingFulfillment($request);

if (isset($response->Errors)) {
    foreach ($response->Errors as $error) {
        printf(
          "%s: %s\n%s\n\n",
          $error->errorId,
          $error->message,
          $error->longMessage
        );
    }
}

if ($response->getStatusCode() === 201) {
    return true; // Success
} else {
    return false;
}

