<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

enum CardBrand: string
{
    case visa = 'visa';
    case mastercard = 'mastercard';
    case verve = 'verve';
    case amex = 'amex';
    case discover = 'discover';
    case diners = 'diners';
    case jcb = 'jcb';
    case unionpay = 'unionpay';
    case unknown = 'unknown';
}

