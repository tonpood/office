var arDetailAction = function () {
  var id = $E('id').value;
  if (id == 0) {
    alert(trans('please save before continuing'));
  } else {
    if (this.id == 'print') {
      window.open(WEB_URL + 'modules/ar/print.php?template=' + $E('template').value + '&id=' + id);
    } else if (this.id == 'account') {
      window.location = 'index.php?module=ar-transaction&src=ar-detail&id=' + id;
    } else if (this.id == 'print_transaction') {
      window.open(WEB_URL + 'modules/ar/print.php?page=transaction&id=' + id, 'print');
    }
  }
};
function initArDetail() {
  var creditor = new Array(),
    patt = /creditor_[0-9]+/;
  var doCalc = function () {
    var total = 0;
    forEach(creditor, function () {
      total += floatval(this.value);
    });
    $E('total').value = new String(total).currFormat();
    if ($E('include_interest').value == 1) {
      var interest = floatval($E('interest').value);
      total = new String(total + (((total * interest) / 100) * (floatval($E('period').value) * floatval($E('period_type').value))));
      $E('aggregate').value = total.currFormat();
      $E('aggregate').readOnly = true;
    } else {
      $E('aggregate').readOnly = false;
    }
  };
  forEach($G('setup_frm').elems('input'), function () {
    if (patt.test(this.id)) {
      $G(this).addEvent('change', doCalc);
      creditor.push(this);
    }
  });
  var SearchGet = function () {
    var q = null,
      value = $E('name').value;
    if (value != '') {
      q = 'name=' + encodeURIComponent(value);
    }
    return q;
  };
  function SearchCallback() {
    $E('name').value = this.name;
    $G('name').replaceClass('invalid', 'valid');
    $G('sex').setValue(this.sex);
    $G('provinceID').setValue(this.provinceID);
    $E('phone').value = this.phone;
    $E('expire_date').value = this.expire_date;
    $E('zipcode').value = this.zipcode;
    $E('address').value = this.address;
    $E('id_card').value = this.id_card;
  }
  function SearchPopulate() {
    var patt = new RegExp('(' + $E('name').value + ')', 'gi');
    return '<p><span class="icon-customer">' + (this.name.unentityify() + ' ' + this.id_card.unentityify() + ' ' + this.phone.unentityify()).replace(patt, '<em>$1</em>') + '</span></p>';
  }
  function SearchRequest(datas) {
    $G('name').reset();
  }
  new GAutoComplete('name', {
    className: "gautocomplete",
    get: SearchGet,
    url: 'index.php/ar/model/autocomplete/findCustomer',
    callBack: SearchCallback,
    populate: SearchPopulate,
    onRequest: SearchRequest
  });
  $G('interest').addEvent('change', doCalc);
  $G('period').addEvent('change', doCalc);
  $G('period_type').addEvent('change', doCalc);
  $G('include_interest').addEvent('change', doCalc);
  callClick('account', arDetailAction);
  callClick('print', arDetailAction);
}

