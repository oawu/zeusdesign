<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class InvoiceTag extends OaModel {

  static $table_name = 'invoice_tags';

  static $has_one = array (
  );

  static $has_many = array (
    array ('invoices', 'class_name' => 'Invoice'),
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function destroy () {
    if (!isset ($this->id))
      return false;

    if ($this->invoices)
      foreach ($this->invoices as $invoice)
        if (!($invoice->invoice_tag_id = 0) && $invoice->save ())
          return false;

    return $this->delete ();
  }
}