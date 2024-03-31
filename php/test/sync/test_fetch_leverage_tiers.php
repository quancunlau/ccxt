<?php
namespace ccxt;

// ----------------------------------------------------------------------------

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

// -----------------------------------------------------------------------------
include_once PATH_TO_CCXT . '/test/base/test_leverage_tier.php';

function test_fetch_leverage_tiers($exchange, $skipped_properties, $symbol) {
    $method = 'fetchLeverageTiers';
    $tiers = $exchange->fetch_leverage_tiers(['symbol']);
    // const format = {
    //     'RAY/USDT': [
    //       {},
    //     ],
    // };
    assert(is_array($tiers), $exchange->id . ' ' . $method . ' ' . $symbol . ' must return an object. ' . $exchange->json($tiers));
    $tier_keys = is_array($tiers) ? array_keys($tiers) : array();
    assert_non_emtpy_array($exchange, $skipped_properties, $method, $tier_keys, $symbol);
    for ($i = 0; $i < count($tier_keys); $i++) {
        $tiers_for_symbol = $tiers[$tier_keys[$i]];
        assert_non_emtpy_array($exchange, $skipped_properties, $method, $tiers_for_symbol, $symbol);
        for ($j = 0; $j < count($tiers_for_symbol); $j++) {
            test_leverage_tier($exchange, $skipped_properties, $method, $tiers_for_symbol[$j]);
        }
    }
}
