<?php

namespace App\Service\Mail\Brevo\Enums;

enum SendConditions: string
{
    case BUYER_SIGNUP = 'buyer.signup';
    case BUYER_MONTHLY_ONCE = 'buyer.monthly.once';
    case BUYER_LONG_INACTIVITY = 'buyer.long.inactivity';
    case SELLER_SIGNUP = 'seller.signup';
    case SELLER_MONTHLY_ONCE = 'seller.monthly.once';
    case SELLER_LONG_INACTIVITY = 'seller.long.inactivity';
    case SELF_PRODUCT_FIRST_GENERATE = 'self.product.first.generate';
    case SELF_PRODUCT_FIRST_5_GENERATE = 'self.product.first.five.generate';
    case FIRST_TEN_DOLLARS_ACHIEVED = 'first.ten.dollars.achieved';
    case BALANCE_BELOW_TWO_DOLLARS_ACHIEVED = 'balance.below.two.dollars.achieved';
    case PURCHASE_TOPUP = 'purchase.topup';
    case FREE_GENERATE_COMPLETED = 'free.generate.completed';
    case FIRST_PAID_PROMPT_GENERATION = 'first.paid.prompt.generation';
}
