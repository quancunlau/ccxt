<?php

namespace ccxtpro;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception; // a common import
use \React\Async;

class ripio extends \ccxt\rest\async\ripio {

    use ClientTrait;

    public function describe() {
        return $this->deep_extend(parent::describe(), array(
            'has' => array(
                'ws' => true,
                'watchOrderBook' => true,
                'watchTrades' => true,
                'watchTicker' => true,
            ),
            'urls' => array(
                'api' => array(
                    'ws' => 'wss://api.exchange.ripio.com/ws/v2/consumer/non-persistent/public/default/',
                ),
            ),
            'options' => array(
                'tradesLimit' => 1000,
                'uuid' => $this->uuid(),
            ),
        ));
    }

    public function watch_trades($symbol = null, $since = null, $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $since, $limit, $params) {
            Async\await($this->load_markets());
            $market = $this->market($symbol);
            $name = 'trades';
            $messageHash = $name . '_' . strtolower($market['id']);
            $url = $this->urls['api']['ws'] . $messageHash . '/' . $this->options['uuid'];
            $subscription = array(
                'name' => $name,
                'symbol' => $symbol,
                'messageHash' => $messageHash,
                'method' => array($this, 'handle_trade'),
            );
            $trades = Async\await($this->watch($url, $messageHash, null, $messageHash, $subscription));
            if ($this->newUpdates) {
                $limit = $trades->getLimit ($symbol, $limit);
            }
            return $this->filter_by_since_limit($trades, $since, $limit, 'timestamp', true);
        }) ();
    }

    public function handle_trade($client, $message, $subscription) {
        //
        //     {
        //         messageId => 'CAAQAA==',
        //         $payload => 'eyJjcmVhdGVkX2F0IjogMTYwMTczNjI0NywgImFtb3VudCI6ICIwLjAwMjAwIiwgInByaWNlIjogIjEwNTkzLjk5MDAwMCIsICJzaWRlIjogIkJVWSIsICJwYWlyIjogIkJUQ19VU0RDIiwgInRha2VyX2ZlZSI6ICIwIiwgInRha2VyX3NpZGUiOiAiQlVZIiwgIm1ha2VyX2ZlZSI6ICIwIiwgInRha2VyIjogMjYxODU2NCwgIm1ha2VyIjogMjYxODU1N30=',
        //         properties => array(),
        //         publishTime => '2020-10-03T14:44:09.881Z'
        //     }
        //
        $payload = $this->safe_string($message, 'payload');
        if ($payload === null) {
            return $message;
        }
        $data = json_decode(base64_decode($payload, $as_associative_array = true));
        //
        //     {
        //         created_at => 1601736247,
        //         amount => '0.00200',
        //         price => '10593.990000',
        //         side => 'BUY',
        //         pair => 'BTC_USDC',
        //         taker_fee => '0',
        //         taker_side => 'BUY',
        //         maker_fee => '0',
        //         taker => 2618564,
        //         maker => 2618557
        //     }
        //
        $symbol = $this->safe_string($subscription, 'symbol');
        $messageHash = $this->safe_string($subscription, 'messageHash');
        $market = $this->market($symbol);
        $trade = $this->parse_trade($data, $market);
        $tradesArray = $this->safe_value($this->trades, $symbol);
        if ($tradesArray === null) {
            $limit = $this->safe_integer($this->options, 'tradesLimit', 1000);
            $tradesArray = new ArrayCache ($limit);
            $this->trades[$symbol] = $tradesArray;
        }
        $tradesArray->append ($trade);
        $client->resolve ($tradesArray, $messageHash);
    }

    public function watch_ticker($symbol, $params = array ()) {
        return Async\async(function () use ($symbol, $params) {
            Async\await($this->load_markets());
            $market = $this->market($symbol);
            $name = 'rate';
            $messageHash = $name . '_' . strtolower($market['id']);
            $url = $this->urls['api']['ws'] . $messageHash . '/' . $this->options['uuid'];
            $subscription = array(
                'name' => $name,
                'symbol' => $symbol,
                'messageHash' => $messageHash,
                'method' => array($this, 'handle_ticker'),
            );
            return Async\await($this->watch($url, $messageHash, null, $messageHash, $subscription));
        }) ();
    }

    public function handle_ticker($client, $message, $subscription) {
        //
        //     {
        //         messageId => 'CAAQAA==',
        //         $payload => 'eyJidXkiOiBbeyJhbW91bnQiOiAiMC4wOTMxMiIsICJ0b3RhbCI6ICI4MzguMDgiLCAicHJpY2UiOiAiOTAwMC4wMCJ9XSwgInNlbGwiOiBbeyJhbW91bnQiOiAiMC4wMDAwMCIsICJ0b3RhbCI6ICIwLjAwIiwgInByaWNlIjogIjkwMDAuMDAifV0sICJ1cGRhdGVkX2lkIjogMTI0NDA0fQ==',
        //         properties => array(),
        //         publishTime => '2020-10-03T10:05:09.445Z'
        //     }
        //
        $payload = $this->safe_string($message, 'payload');
        if ($payload === null) {
            return $message;
        }
        $data = json_decode(base64_decode($payload, $as_associative_array = true));
        //
        //     {
        //         "pair" => "BTC_BRL",
        //         "last_price" => "68558.59",
        //         "low" => "54736.11",
        //         "high" => "70034.68",
        //         "variation" => "8.75",
        //         "volume" => "10.10537"
        //     }
        //
        $ticker = $this->parse_ticker($data);
        $timestamp = $this->parse8601($this->safe_string($message, 'publishTime'));
        $ticker['timestamp'] = $timestamp;
        $ticker['datetime'] = $this->iso8601($timestamp);
        $symbol = $ticker['symbol'];
        $this->tickers[$symbol] = $ticker;
        $messageHash = $this->safe_string($subscription, 'messageHash');
        if ($messageHash !== null) {
            $client->resolve ($ticker, $messageHash);
        }
        return $message;
    }

    public function watch_order_book($symbol, $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $limit, $params) {
            Async\await($this->load_markets());
            $market = $this->market($symbol);
            $name = 'orderbook';
            $messageHash = $name . '_' . strtolower($market['id']);
            $url = $this->urls['api']['ws'] . $messageHash . '/' . $this->options['uuid'];
            $client = $this->client($url);
            $subscription = array(
                'name' => $name,
                'symbol' => $symbol,
                'messageHash' => $messageHash,
                'method' => array($this, 'handle_order_book'),
            );
            if (!(is_array($client->subscriptions) && array_key_exists($messageHash, $client->subscriptions))) {
                $this->orderbooks[$symbol] = $this->order_book(array());
                $client->subscriptions[$messageHash] = $subscription;
                $options = $this->safe_value($this->options, 'fetchOrderBookSnapshot', array());
                $delay = $this->safe_integer($options, 'delay', $this->rateLimit);
                // fetch the snapshot in a separate async call after a warmup $delay
                $this->delay($delay, array($this, 'fetch_order_book_snapshot'), $client, $subscription);
            }
            $orderbook = Async\await($this->watch($url, $messageHash, null, $messageHash, $subscription));
            return $orderbook->limit ($limit);
        }) ();
    }

    public function fetch_order_book_snapshot($client, $subscription) {
        return Async\async(function () use ($client, $subscription) {
            $symbol = $this->safe_string($subscription, 'symbol');
            $messageHash = $this->safe_string($subscription, 'messageHash');
            try {
                // todo => this is a synch blocking call in ccxt.php - make it async
                $snapshot = Async\await($this->fetch_order_book($symbol));
                $orderbook = $this->orderbooks[$symbol];
                $messages = $orderbook->cache;
                $orderbook->reset ($snapshot);
                // unroll the accumulated deltas
                for ($i = 0; $i < count($messages); $i++) {
                    $message = $messages[$i];
                    $this->handle_order_book_message($client, $message, $orderbook);
                }
                $this->orderbooks[$symbol] = $orderbook;
                $client->resolve ($orderbook, $messageHash);
            } catch (Exception $e) {
                $client->reject ($e, $messageHash);
            }
        }) ();
    }

    public function handle_order_book($client, $message, $subscription) {
        $messageHash = $this->safe_string($subscription, 'messageHash');
        $symbol = $this->safe_string($subscription, 'symbol');
        $orderbook = $this->safe_value($this->orderbooks, $symbol);
        if ($orderbook === null) {
            return $message;
        }
        if ($orderbook['nonce'] === null) {
            $orderbook->cache[] = $message;
        } else {
            $this->handle_order_book_message($client, $message, $orderbook);
            $client->resolve ($orderbook, $messageHash);
        }
        return $message;
    }

    public function handle_order_book_message($client, $message, $orderbook) {
        //
        //     {
        //         messageId => 'CAAQAA==',
        //         $payload => 'eyJidXkiOiBbeyJhbW91bnQiOiAiMC4wOTMxMiIsICJ0b3RhbCI6ICI4MzguMDgiLCAicHJpY2UiOiAiOTAwMC4wMCJ9XSwgInNlbGwiOiBbeyJhbW91bnQiOiAiMC4wMDAwMCIsICJ0b3RhbCI6ICIwLjAwIiwgInByaWNlIjogIjkwMDAuMDAifV0sICJ1cGRhdGVkX2lkIjogMTI0NDA0fQ==',
        //         properties => array(),
        //         publishTime => '2020-10-03T10:05:09.445Z'
        //     }
        //
        $payload = $this->safe_string($message, 'payload');
        if ($payload === null) {
            return $message;
        }
        $data = json_decode(base64_decode($payload, $as_associative_array = true));
        //
        //     {
        //         "buy" => array(
        //             array("amount" => "0.05000", "total" => "532.77", "price" => "10655.41")
        //         ),
        //         "sell" => array(
        //             array("amount" => "0.00000", "total" => "0.00", "price" => "10655.41")
        //         ),
        //         "updated_id" => 99740
        //     }
        //
        $nonce = $this->safe_integer($data, 'updated_id');
        if ($nonce > $orderbook['nonce']) {
            $asks = $this->safe_value($data, 'sell', array());
            $bids = $this->safe_value($data, 'buy', array());
            $this->handle_deltas($orderbook['asks'], $asks, $orderbook['nonce']);
            $this->handle_deltas($orderbook['bids'], $bids, $orderbook['nonce']);
            $orderbook['nonce'] = $nonce;
            $timestamp = $this->parse8601($this->safe_string($message, 'publishTime'));
            $orderbook['timestamp'] = $timestamp;
            $orderbook['datetime'] = $this->iso8601($timestamp);
        }
        return $orderbook;
    }

    public function handle_delta($bookside, $delta) {
        $price = $this->safe_float($delta, 'price');
        $amount = $this->safe_float($delta, 'amount');
        $bookside->store ($price, $amount);
    }

    public function handle_deltas($bookside, $deltas) {
        for ($i = 0; $i < count($deltas); $i++) {
            $this->handle_delta($bookside, $deltas[$i]);
        }
    }

    public function ack($client, $messageId) {
        return Async\async(function () use ($client, $messageId) {
            // the exchange requires acknowledging each received message
            Async\await($client->send (array( 'messageId' => $messageId )));
        }) ();
    }

    public function handle_message($client, $message) {
        //
        //     {
        //         $messageId => 'CAAQAA==',
        //         payload => 'eyJidXkiOiBbeyJhbW91bnQiOiAiMC4wNTAwMCIsICJ0b3RhbCI6ICI1MzIuNzciLCAicHJpY2UiOiAiMTA2NTUuNDEifV0sICJzZWxsIjogW3siYW1vdW50IjogIjAuMDAwMDAiLCAidG90YWwiOiAiMC4wMCIsICJwcmljZSI6ICIxMDY1NS40MSJ9XSwgInVwZGF0ZWRfaWQiOiA5OTc0MH0=',
        //         properties => array(),
        //         publishTime => '2020-09-30T17:35:27.851Z'
        //     }
        //
        $messageId = $this->safe_string($message, 'messageId');
        if ($messageId !== null) {
            // the exchange requires acknowledging each received $message
            $this->spawn(array($this, 'ack'), $client, $messageId);
        }
        $keys = is_array($client->subscriptions) ? array_keys($client->subscriptions) : array();
        $firstKey = $this->safe_string($keys, 0);
        $subscription = $this->safe_value($client->subscriptions, $firstKey, array());
        $method = $this->safe_value($subscription, 'method');
        if ($method !== null) {
            return $method($client, $message, $subscription);
        }
        return $message;
    }
}
