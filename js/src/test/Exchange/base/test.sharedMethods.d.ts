import { Exchange } from "../../../../ccxt";
declare function logTemplate(exchange: Exchange, method: string, entry: object): string;
declare function isTemporaryFailure(e: any): boolean;
declare function assertType(exchange: Exchange, skippedProperties: object, entry: object, key: any, format: any): boolean;
declare function assertStructure(exchange: Exchange, skippedProperties: object, method: string, entry: object, format: any, emptyAllowedFor?: any[]): void;
declare function assertTimestamp(exchange: Exchange, skippedProperties: object, method: string, entry: object, nowToCheck?: any, keyNameOrIndex?: any): void;
declare function assertTimestampAndDatetime(exchange: Exchange, skippedProperties: object, method: string, entry: object, nowToCheck?: any, keyNameOrIndex?: any): void;
declare function assertCurrencyCode(exchange: Exchange, skippedProperties: object, method: string, entry: object, actualCode: any, expectedCode?: any): void;
declare function assertValidCurrencyIdAndCode(exchange: Exchange, skippedProperties: object, method: string, entry: object, currencyId: any, currencyCode: any): void;
declare function assertSymbol(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any, expectedSymbol?: any): void;
declare function assertSymbolInMarkets(exchange: Exchange, skippedProperties: object, method: string, symbol: string): void;
declare function assertGreater(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any, compareTo: any): void;
declare function assertGreaterOrEqual(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any, compareTo: any): void;
declare function assertLess(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any, compareTo: any): void;
declare function assertLessOrEqual(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any, compareTo: any): void;
declare function assertEqual(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any, compareTo: any): void;
declare function assertNonEqual(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any, compareTo: any): void;
declare function assertInArray(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any, expectedArray: any): void;
declare function assertFeeStructure(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any): void;
declare function assertTimestampOrder(exchange: Exchange, method: string, codeOrSymbol: string, items: any, ascending?: boolean): void;
declare function assertInteger(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any): void;
declare function checkPrecisionAccuracy(exchange: Exchange, skippedProperties: object, method: string, entry: object, key: any): void;
declare function removeProxyOptions(exchange: Exchange, skippedProperties: object): any[];
declare function setProxyOptions(exchange: Exchange, skippedProperties: object, proxyUrl: any, httpProxy: any, httpsProxy: any, socksProxy: any): void;
declare function assertNonEmtpyArray(exchange: any, skippedProperties: any, method: any, entry: any, hint?: any): void;
declare function assertRoundMinuteTimestamp(exchange: any, skippedProperties: any, method: any, entry: any, key: any): void;
declare const _default: {
    logTemplate: typeof logTemplate;
    isTemporaryFailure: typeof isTemporaryFailure;
    assertTimestamp: typeof assertTimestamp;
    assertTimestampAndDatetime: typeof assertTimestampAndDatetime;
    assertStructure: typeof assertStructure;
    assertSymbol: typeof assertSymbol;
    assertSymbolInMarkets: typeof assertSymbolInMarkets;
    assertCurrencyCode: typeof assertCurrencyCode;
    assertInArray: typeof assertInArray;
    assertFeeStructure: typeof assertFeeStructure;
    assertTimestampOrder: typeof assertTimestampOrder;
    assertGreater: typeof assertGreater;
    assertGreaterOrEqual: typeof assertGreaterOrEqual;
    assertLess: typeof assertLess;
    assertLessOrEqual: typeof assertLessOrEqual;
    assertEqual: typeof assertEqual;
    assertNonEqual: typeof assertNonEqual;
    assertInteger: typeof assertInteger;
    checkPrecisionAccuracy: typeof checkPrecisionAccuracy;
    assertValidCurrencyIdAndCode: typeof assertValidCurrencyIdAndCode;
    assertType: typeof assertType;
    removeProxyOptions: typeof removeProxyOptions;
    setProxyOptions: typeof setProxyOptions;
    assertNonEmtpyArray: typeof assertNonEmtpyArray;
    assertRoundMinuteTimestamp: typeof assertRoundMinuteTimestamp;
};
export default _default;
