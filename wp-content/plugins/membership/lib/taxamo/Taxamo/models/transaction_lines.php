<?php
/**
 *  Copyright 2014 Taxamo, Ltd.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

/**
 * $model.description$
 *
 * NOTE: This class is auto generated by the swagger code generator program. Do not edit the class manually.
 *
 */
class Transaction_lines {

  static $swaggerTypes = array(
      'custom_fields' => 'array[custom_fields]',
      'additional_currencies' => 'additional_currencies',
      'custom_id' => 'string',
      'deducted_tax_amount' => 'number',
      'product_type' => 'string',
      'quantity' => 'number',
      'tax_amount' => 'number',
      'unit_price' => 'number',
      'unit_of_measure' => 'string',
      'total_amount' => 'number',
      'tax_rate' => 'number',
      'refunded_tax_amount' => 'number',
      'line_key' => 'string',
      'amount' => 'number',
      'id' => 'number',
      'refunded_total_amount' => 'number',
      'informative' => 'bool',
      'description' => 'string',
      'product_code' => 'string',
      'supply_date' => 'string',
      'tax_name' => 'string'

    );

  /**
  * Custom fields, stored as key-value pairs. This property is not processed and used mostly with Taxamo-built helpers.
  */
  public $custom_fields; // array[custom_fields]
  /**
  * Additional currency information - can be used to receive additional information about invoice in another currency.
  */
  public $additional_currencies; // additional_currencies
  /**
  * Custom id, provided by ecommerce software.
  */
  public $custom_id; // string
  /**
  * Deducted tax amount, calculated by taxmo.
  */
  public $deducted_tax_amount; // number
  /**
  * Product type, according to dictionary /dictionaries/product_types. 
  */
  public $product_type; // string
  /**
  * Quantity Defaults to 1.
  */
  public $quantity; // number
  /**
  * Tax amount, calculated by taxamo.
  */
  public $tax_amount; // number
  /**
  * Unit price.
  */
  public $unit_price; // number
  /**
  * Unit of measure.
  */
  public $unit_of_measure; // string
  /**
  * Total amount. Required if amount is not provided.
  */
  public $total_amount; // number
  /**
  * Tax rate, calculated by taxamo. Can be overwritten when informative field is true.
  */
  public $tax_rate; // number
  /**
  * Refunded tax amount, calculated by taxmo.
  */
  public $refunded_tax_amount; // number
  /**
  * Generated line key.
  */
  public $line_key; // string
  /**
  * Amount. Required if total amount is not provided.
  */
  public $amount; // number
  /**
  * Generated id.
  */
  public $id; // number
  /**
  * Refunded total amount, calculated by taxmo.
  */
  public $refunded_total_amount; // number
  /**
  * If the line is provided for informative purposes. Such line can have :tax-rate and/or :tax-name - if not, API will calculate missing values according to product type and country of residence.
  */
  public $informative; // bool
  /**
  * Line contents description.
  */
  public $description; // string
  /**
  * Internal product code, used for invoicing for example.
  */
  public $product_code; // string
  /**
  * Date of supply in yyyy-MM-dd format.
  */
  public $supply_date; // string
  /**
  * Tax name, calculated by taxamo.  Can be overwritten when informative field is true.
  */
  public $tax_name; // string
  }

